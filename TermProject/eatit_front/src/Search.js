import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import Input from '@mui/material/Input';
import Button from '@mui/material/Button';
import Card from '@mui/material/Card';
import CardContent from '@mui/material/CardContent';
import CardActions from '@mui/material/CardActions';
import Typography from '@mui/material/Typography';


export function Search() {
  const cardStyle = {
    height: '100%',
    display: 'flex',
    flexDirection: 'column',
    justifyContent: 'space-between',
    backgroundColor: 'lightgray',
    '&:hover': {
      backgroundColor: 	'#B0C4DE',
    },
  };

  const buttonStyle = {
    backgroundColor: 'black',
    color: 'white',
    '&:hover': {
      backgroundColor: '#696969',
    },
    margin: '5px',
  };

  const gridContainerStyle = {
    display: 'grid',
    gridTemplateColumns: 'repeat(auto-fill, minmax(500px, 1fr))', 
    gap: '1rem', 
    padding: '1rem',
  };

  const [recipes, setRecipes] = useState([]);
  const [searchInput, setSearchInput] = useState('');
  const navigate = useNavigate();

  const handleSearchInputChange = (event) => {
    setSearchInput(event.target.value);
  };

  const [selectedIngredients, setSelectedIngredients] = useState([]);

  const toggleIngredient = (ingredient) => {
    setSelectedIngredients((prev) => {
      if (prev.includes(ingredient)) {
        return prev.filter((item) => item !== ingredient);
      } else {
        return [...prev, ingredient];
      }
    });
  };

  const handleSearch = (event) => {
    event.preventDefault(); 
    const inputIngredients = searchInput.split(',').map(ingredient => ingredient.trim()).filter(ingredient => ingredient);
    const combinedIngredients = [...new Set([...selectedIngredients, ...inputIngredients])];

    navigate(`/recipe-list/${encodeURIComponent(combinedIngredients.join(','))}`);
};

  const handleViewRecipe = (recipeName) => {
    navigate(`/recipe/${encodeURIComponent(recipeName)}`);
  };

  useEffect(() => {
    fetch('http://localhost:8000/api/get-random-recipes/', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
    })
      .then(response => response.json())
      .then(data => setRecipes(data.recipes))
      .catch(error => console.error('Error fetching data:', error));
  }, []);

  return (
    <div
      className="flex flex-col h-screen px-4 py-6 md:px-8 md:py-10 justify-center items-center"
      style={{ margin: '50px' }}
    >
      <div className="w-full max-w-md mx-auto space-y-4">
        <h1 className="text-2xl font-bold text-center mb-6"> Eat It! 냉장고 속 재료 입력하기</h1>
        <div>
          {selectedIngredients.length > 0 && (
            <p>추가된 재료: {selectedIngredients.join(', ')}</p>
          )}
        </div>
        <div className="flex items-center space-x-2">
          <Input placeholder="재료를 입력하세요 (입력예시: 참기름, 고기, 당근)" type="text" 
            value={searchInput}
            onChange={(e) => setSearchInput(e.target.value)}
            sx={{
              width: 'calc(100% - 0px)',
              margin: '0 0px',
            }}
          />
          <Button variant="contained" type="submit" sx={buttonStyle} onClick={handleSearch}>Search</Button>
        </div>
        <br></br>
        <div className="text-center">
          <h2 className="text-xl font-semibold mb-4">냉장고 속 재료 빠르게 추가하기</h2>
          <div className="flex flex-wrap justify-center gap-2">
          {['설탕', '양파', '소금', '마늘', '참기름', '간장', '물', '파', '후추', '고춧가루', '깨', '고추장', '고기', '올리고당', '식용유', '맛술', '계란', '고추', '당근', '굴소스', '버터', '감자', '우유', '밥', '두부', '마요네즈', '박력분', '들기름', '오이', '된장', '올리브유', '물엿', '깻잎', '베이컨', '꿀', '무', '부추', '파프리카', '식빵', '콩나물', '밀가루'].map((ingredient) => (
            <Button key={ingredient} variant="contained" sx={buttonStyle} onClick={() => toggleIngredient(ingredient)}>
              {ingredient}
            </Button>
          ))}
          </div>
        </div>
      </div>
      <br></br><br></br>
      <div className="w-full mt-6">
      <h2 className="text-xl font-semibold mb-4">랜덤 추천 요리</h2>
        <div style={gridContainerStyle}>
        {recipes.map((recipe, index) => (
            <Card key={index} sx={cardStyle}>
              <CardContent>
                <Typography gutterBottom variant="h5" component="div">
                  {recipe.name}
                </Typography>
                <Typography variant="body2" color="text.secondary">
                  필요한 재료: {recipe.ingredients}
                </Typography>
              </CardContent>
              <CardActions>
                <Button size="small" variant="contained" onClick={() => handleViewRecipe(recipe.name)}>레시피 확인하기</Button>
              </CardActions>
            </Card>
          ))}
        </div>
      </div>
    </div>
  );
}
