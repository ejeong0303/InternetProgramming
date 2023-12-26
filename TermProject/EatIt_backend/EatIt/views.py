import re
from bson.regex import Regex
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.views.decorators.http import require_http_methods
from pymongo import MongoClient
import json
import random

@csrf_exempt  
@require_http_methods(["POST"])  
def get_random_recipes(request):
    client = MongoClient('mongodb://localhost:27017/')
    db = client.EatIt
    collection = db.recipes

    count = collection.count_documents({})
    indices = random.sample(range(count), 6)

    recipes = []
    for index in indices:
        recipe_cursor = collection.find().skip(index).limit(1)
        for recipe in recipe_cursor:
            recipes.append({
                'name': recipe['name'],
                'ingredients': recipe['ingredients']
            })
    
    return JsonResponse({'recipes': recipes})

@csrf_exempt
@require_http_methods(["POST"])
def get_recipe_details(request):
    client = MongoClient('mongodb://localhost:27017/')
    db = client.EatIt
    collection = db.recipes

    data = json.loads(request.body)
    recipe_name = data.get("name")

    recipe = collection.find_one({"name": recipe_name}, {"_id": 0, "name": 1, "ingredients": 1, "recipe": 1})
    
    return JsonResponse(recipe if recipe else {"error": "Recipe not found"})

@csrf_exempt
@require_http_methods(["POST"])
def search_recipes(request):
    client = MongoClient('mongodb://localhost:27017/')
    db = client.EatIt
    collection = db.recipes

    data = json.loads(request.body)
    input_ingredients = data.get("ingredients", [])

    if isinstance(input_ingredients, str):
        ingredients_patterns = [re.escape(ingredient.strip()) for ingredient in input_ingredients.split(',')]
    elif isinstance(input_ingredients, list):
        ingredients_patterns = [re.escape(ingredient.strip()) for ingredient in input_ingredients]
    else:
        return JsonResponse({"error": "Invalid ingredients format"}, status=400)

    regex_patterns = [Regex(f".*{pattern}.*", "i") for pattern in ingredients_patterns]

    query = {"ingredients": {"$all": regex_patterns}}
    recipes_cursor = collection.find(query, {"_id": 0, "name": 1, "ingredients": 1})

    all_recipes = list(recipes_cursor)

    if len(all_recipes) == 0:
        return JsonResponse({"message": "No matching recipes found"}, status=404)
    elif len(all_recipes) <= 6:
        selected_recipes = all_recipes
    else:
        selected_recipes = random.sample(all_recipes, 6)

    return JsonResponse({"recipes": selected_recipes})
