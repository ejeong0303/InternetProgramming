import requests
from bs4 import BeautifulSoup
import json
from pymongo import MongoClient
import time
import random
from requests.adapters import HTTPAdapter
from requests.packages.urllib3.util.retry import Retry
from requests.exceptions import SSLError, ConnectionError

def requests_retry_session(retries=5, backoff_factor=1.0):
    session = requests.Session()
    retry = Retry(
        total=retries,
        read=retries,
        connect=retries,
        backoff_factor=backoff_factor,
        status_forcelist=(500, 502, 503, 504),
    )
    adapter = HTTPAdapter(max_retries=retry)
    session.mount('http://', adapter)
    session.mount('https://', adapter)
    return session

def get_recipe_info(recipe_url):
    try:
        response = requests_retry_session().get(recipe_url, headers={'User-Agent': 'Mozilla/5.0'})
        if response.status_code != 200:
            print("HTTP response error:", response.status_code)
            return None

        soup = BeautifulSoup(response.text, 'html.parser')
        script_tag = soup.find('script', {'type': 'application/ld+json'})
        if script_tag:
            try:
                data = json.loads(script_tag.string)
                name = data.get('name', 'No Name')
                ingredients = ','.join(data.get('recipeIngredient', []))
                recipe_steps = [step['text'] for step in data.get('recipeInstructions', [])]
                images = data.get('image', [])
                image_url = images[1] if len(images) > 1 else None

                return {
                    'name': name, 
                    'ingredients': ingredients, 
                    'recipe': recipe_steps,
                    'image_url': image_url 
                }
            except json.JSONDecodeError as e:
                print(f"JSON decoding failed for URL {recipe_url} with error: {e}")
                return None
            except SSLError as e:
                print(f"SSL error occurred for URL {recipe_url}: {e}")
                return None
    except (SSLError, ConnectionError) as e:
        print(f"Network error occurred for URL {recipe_url}: {e}")
        return None

def food_info(start_page, end_page):
    all_recipes = []

    for page in range(start_page, end_page + 1):
        try:
            url = f"https://m.10000recipe.com/recipe/list.html?order=reco&page={page}"
            response = requests_retry_session().get(url, headers={'User-Agent': 'Mozilla/5.0'})
            if response.status_code != 200:
                print("HTTP response error:", response.status_code)
                continue

            soup = BeautifulSoup(response.text, 'html.parser')
            recipe_elements = soup.find_all('div', {'class': 'media'})

            for recipe_element in recipe_elements:
                recipe_link = recipe_element.find('a')['href']
                recipe_url = f'https://m.10000recipe.com/{recipe_link}'
                recipe_info = get_recipe_info(recipe_url)
                if recipe_info:
                    all_recipes.append(recipe_info)
                time.sleep(random.uniform(0.5, 2.0))
        except Exception as e:
            print(f"An error occurred on page {page}: {e}")    
    return all_recipes

def save_to_mongodb(recipes):

    client = MongoClient('mongodb://localhost:27017/') 
    db = client['EatIt']  
    collection = db['recipes+image']  

    if recipes:
        collection.insert_many(recipes)
        print(f"{len(recipes)} recipes have been inserted into MongoDB.")
    else:
        print("No recipes to insert.")


recipes = food_info(4, 500) # Crawl from page 1 to 500

# for recipe in recipes:
#     print(recipe)

save_to_mongodb(recipes)
