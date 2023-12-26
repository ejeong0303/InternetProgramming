import React from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import { Search } from './Search'; // Import your SearchPage component
import RecipeDetails from './RecipeDetails'; // Import your RecipeDetails component
import RecipeList from './RecipeList';
import './App.css';
import './index.css';

function App() {
  return (
    <Router>
        <Routes>
          <Route path="/" element={<Search />} />
          <Route path="/recipe/:id" element={<RecipeDetails />} />
          <Route path="/recipe-list/:ingredients" element={<RecipeList />} />
        </Routes>
    </Router>
  );
}

export default App;
