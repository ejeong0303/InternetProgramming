import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import Card from '@mui/material/Card';
import CardContent from '@mui/material/CardContent';
import Typography from '@mui/material/Typography';
import Button from '@mui/material/Button';

export default function RecipeList() {
  const [recipes, setRecipes] = useState([]);
  const { ingredients } = useParams();
  const navigate = useNavigate();

  useEffect(() => {
    if (ingredients) {
      fetch('http://localhost:8000/api/search-recipes/', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ ingredients: decodeURIComponent(ingredients).split(',') }),
      })
      .then(response => {
        if (response.status === 404) {
          alert("만족하는 레시피가 없습니다. 다시 검색하세요.");
          navigate('/');
          return { recipes: [] };
        }
        return response.json();
      })
      .then(data => setRecipes(data.recipes))
      .catch(error => console.error('Error fetching data:', error));
    }
  }, [ingredients, navigate]);

  const handleViewRecipe = (recipeName) => {
    navigate(`/recipe/${encodeURIComponent(recipeName)}`);
  };

  const cardStyle = {
    backgroundColor: 'lightgray',
    '&:hover': {
      backgroundColor: '#B0C4DE',
    },
    margin: '20px',
    marginLeft: '20px'
  };

  const buttonStyle = {
    backgroundColor: 'black',
    color: 'white',
    width: '130px',
    '&:hover': {
      backgroundColor: '#696969',
    },
    marginTop: 'auto',
  };

  return (
    <div className="flex flex-col px-4 py-6 md:px-8 md:py-10" style={{ margin: '50px' }}>
      <main className="container mx-auto">
        <header className="mb-10">
        <h1 className="text-2xl font-bold text-center mb-6">Eat It! 추천 레시피 리스트</h1>
        </header>
        <br></br>
        <section className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {recipes.map((recipe, index) => (
            <Card key={index} sx={cardStyle}>
              <CardContent>
                <Typography variant="h5" component="h2" className="font-bold">
                  {recipe.name}
                </Typography>
                <ul className="list-disc ml-5">
                  {recipe.ingredients.split(',').map((ingredient, i) => (
                    <li key={i}>{ingredient}</li>
                  ))}
                </ul>
                <br></br>
                <Button sx={buttonStyle} onClick={() => handleViewRecipe(recipe.name)}>
                  레시피 확인하기
                </Button>
              </CardContent>
            </Card>
          ))}
        </section>
      </main>
    </div>
  );
}
