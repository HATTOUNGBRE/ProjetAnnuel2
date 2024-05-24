
import React from "react";
import Header from "./components/Header";
import Main from "./components/Main";
import Contact from "./components/Contact";
import Footer from "./components/Footer";

// src/App.js

import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
// import Home from './components/Home';
// import About from './components/About';
import Nav from './components/Nav';  // Assurez-vous dimporter votre composant Nav
// import Reservation from './components/Reservation';
 import DashBoard from './components/DashBoard';
 import Prestation from './components/Prestation';
import Connexion from './components/Connexion';
import Inscription from './components/Inscription';

function App() {
    return (
        <Router>
            <Header />
            <Routes>
                <Route exact path="/" element={<Main/>} />
                
                <Route path="/components/inscription" element={<Inscription />} />
                <Route path="/components/connexion" element={<Connexion />} />
                <Route path="/components/Prestation" element={<Prestation />} />
                <Route path="/components/DashBoard" element={<DashBoard />} />



            </Routes>
            <Footer />
        </Router>
    );
}

export default App;

