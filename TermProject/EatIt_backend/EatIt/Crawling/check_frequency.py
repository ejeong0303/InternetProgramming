from pymongo import MongoClient
from collections import Counter

client = MongoClient('mongodb://localhost:27017/')
db = client.EatIt
collection = db.recipes

recipes = collection.find({}, {"ingredients": 1})

ingredient_counter = Counter()

for recipe in recipes:
    ingredients = recipe.get('ingredients', '').split(',')
    for ingredient in ingredients:
        ingredient_name = ingredient.split(' ')[0]
        ingredient_counter[ingredient_name] += 1

top_50_ingredients = ingredient_counter.most_common(60)
for ingredient, count in top_50_ingredients:
    print(f"{ingredient}")

