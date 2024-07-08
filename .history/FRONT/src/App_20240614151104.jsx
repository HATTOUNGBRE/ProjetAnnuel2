import React, { useContext, useEffect } from "react";
import { BrowserRouter as Router, Route, Routes, Navigate } from 'react-router-dom';
import Header from "./components/Header";
import Main from "./components/Main";
import Contact from "./components/Contact";
import Nav from "./components/Nav";
import Footer from "./components/Footer";
import CatUser from "./components/CatUser";
import Login from './components/Login';
import LogOut from "./components/LogOut";
import Account from "./components/Account";
import Inscription from './components/Inscription';
import VoyageurDashboard from './components/dashboard/VoyageurDashboard';
import ProprietaireDashboard from './components/dashboard/ProprietaireDashboard';
import PrestataireDashboard from './components/dashboard/PrestataireDashboard';
import Error400 from './components/Error400';
import AuthContext from './components/AuthContext';
import ProprietairePrestation from "./components/proprio/Prestation";
import ProprietaireReservation from "./components/proprio/ProprietaireReservation";
import PrestataireReservation from "./components/prestataire/PrestataireReservation";
import AddProperty from "./components/proprio/AddProperty";
import CreatePrestation from "./components/proprio/CreatePrestation";

function App() {
    const { isLoggedIn, userRole, userId, userName, userSurname, category } = useContext(AuthContext);

    useEffect(() => {
        console.log("UserRole:", userRole);
        console.log("UserId:", userId);
        console.log("UserName:", userName);
        console.log("UserSurname:", userSurname);
        console.log("CategoryUserId:", category);
    }, [userRole, userId, userName, userSurname, category]);

    const getDashboardComponent = (role) => {
        switch (role) {
            case 'voyageur':
                return <VoyageurDashboard />;
            case 'proprietaire':
                return <ProprietaireDashboard />;
            case 'prestataire':
                return <PrestataireDashboard />;
            default:
                return <Error400 />;
        }
    };

    const ProtectedRoute = ({ element, allowedRole }) => {
        if (!isLoggedIn) {
            return <Navigate to="/login" />;
        }
        return userRole === allowedRole ? element : <Navigate to="/error400" />;
    };

    return (
        <Router>
            <Header isLoggedIn={isLoggedIn} userRole={userRole} />
            <Routes>
                <Route exact path="/" element={<Main isLoggedIn={isLoggedIn} userRole={userRole} />} />
                <Route path="/components/inscription" element={<Inscription />} />
                <Route path="/components/catUser" element={<CatUser />} />
                <Route path="/contact" element={<Contact />} />
                <Route path="/login" element={<Login />} />
                <Route path={`/${userRole}/Prestation`} element={<ProtectedRoute element={<ProprietairePrestation />} allowedRole={userRole} />} />
                <Route path={`/${userRole}/Reservation`} element={<ProtectedRoute element={userRole === 'proprietaire' ? <ProprietaireReservation /> : <PrestataireReservation />} allowedRole={userRole} />} />
                <Route path="/components/dashboard/voyageur" element={<ProtectedRoute element={<VoyageurDashboard />} allowedRole="voyageur" />} />
                <Route path="/components/dashboard/proprietaire" element={<ProtectedRoute element={<ProprietaireDashboard />} allowedRole="proprietaire" />} />
                <Route path="/components/dashboard/prestataire" element={<ProtectedRoute element={<PrestataireDashboard />} allowedRole="prestataire" />} />
                <Route path="/components/nav" element={<Nav isLoggedIn={isLoggedIn} userRole={userRole} />} />
                <Route path="/logout" element={<LogOut />} />
                <Route path="/account" element={<Account userId={userId} />} />
                <Route path="/error400" element={<Error400 />} />
                <Route path="/add-property" element={<ProtectedRoute element={<AddProperty />} allowedRole="proprietaire" />} />
                <Route path="/create-prestation" element={<ProtectedRoute element={<CreatePrestation />} allowedRole="proprietaire" />} />
            </Routes>
            <Footer />
        </Router>
    );
}

export default App;
