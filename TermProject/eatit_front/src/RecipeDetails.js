import React, { useEffect, useState } from 'react';
import { useParams, useNavigate } from 'react-router-dom';
import Card from '@mui/material/Card';
import CardContent from '@mui/material/CardContent';
import Typography from '@mui/material/Typography';
import ArrowBackIcon from '@mui/icons-material/ArrowBack';
import Button from '@mui/material/Button';

export default function RecipeDetails() {

  const cardStyle = {
    height: '100%',
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'space-between',
    backgroundColor: 'lightgray',
  };

  const [recipeDetails, setRecipeDetails] = useState(null);
  const { id: recipeName } = useParams();
  const navigate = useNavigate();

  useEffect(() => {
    fetch(`http://localhost:8000/api/get-recipe-details/`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({ name: decodeURIComponent(recipeName) }),
    })
    .then(response => response.json())
    .then(data => setRecipeDetails(data))
    .catch(error => console.error('Error fetching recipe details:', error));
  }, [recipeName]);

  if (!recipeDetails) return <div>Loading...</div>;

  const handleBackToSearch = () => {
    navigate('/');
  };

  return (
    <div className="flex flex-col px-4 py-6 md:px-8 md:py-10" style={{ margin: '50px' }}>
    <main className="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 relative">
      <Card className="grid lg:grid-cols-2 gap-6" sx={cardStyle}>
        <CardContent>
          <Typography variant="h3" component="h1" className="font-bold">
            {recipeDetails.name}
          </Typography>
          <br></br><br></br>
          <div className="mt-6 space-y-6">
            <div>
              <Typography variant="h6" component="h2" className="font-semibold">
                필요한 재료: 
              </Typography>
              <ul className="list-disc pl-5 mt-2 space-y-1">
                {recipeDetails.ingredients.split(',').map((ingredient, index) => (
                  <li key={index}>{ingredient}</li>
                ))}
              </ul>
            </div>
            <div>
            <br></br>
              <Typography variant="h6" component="h2" className="font-semibold">
                조리 순서:
              </Typography>
              <ol className="list-decimal pl-5 mt-2 space-y-1">
                {recipeDetails.recipe.map((step, index) => (
                  <li key={index}>{step}</li>
                ))}
              </ol>
            </div>
          </div>
        </CardContent>
      </Card>
      <br></br>
      <Button
        variant="contained"
        color="primary"
        startIcon={<ArrowBackIcon />}
        className="absolute left-4 bottom-4 p-2 rounded-lg text-white"
        onClick={handleBackToSearch}
      >
        냉장고 재료로 다시 검색하기
      </Button>
      <br></br>
    </main>
    </div>
  );
}
