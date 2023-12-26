from django.urls import path
from EatIt import views

urlpatterns = [
    path('api/get-random-recipes/', views.get_random_recipes, name='get_random_recipes'),
    path('api/get-recipe-details/', views.get_recipe_details, name='get_recipe_details'),
    path('api/search-recipes/', views.search_recipes, name='search_recipes'),
]
