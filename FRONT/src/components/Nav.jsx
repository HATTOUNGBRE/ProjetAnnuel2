import { Link } from "react-router-dom";
import { useState } from 'react';
import { Menu, X } from "lucide-react";




const NavLinks = () => {
    return (
        <>
            <div className='flex space-x-8'>
                <Link to='/components/Réservation'>
                    <button className="bg-pcs-250 hover:bg-pcs-300 text-white font-bold py-2 px-8 rounded">
                        Réservation
                    </button>
                </Link>

                <Link to='/components/Dashboard'>
                    <button className="bg-pcs-250 hover:bg-pcs-300 text-white font-bold py-2 px-8 rounded">
                        Dashboard
                    </button>
                </Link>

                <Link to='/components/Prestation'>
                    <button className="bg-pcs-250 hover:bg-pcs-300 text-white font-bold py-2 px-8 rounded">
                        Prestations
                    </button>
                </Link>
            </div>
            <div className='flex space-x-8'>
                <Link to='/components/Connexion'>
                    <button className="bg-pcs-400 hover:bg-pcs-700 text-white font-bold py-2 px-8 rounded">
                        Log In
                    </button>
                </Link>
                <Link to='/components/Inscription'>
                    <button className="bg-pcs-300 hover:bg-pcs-600 text-white font-bold py-2 px-8 rounded">
                        Sign Up
                    </button>
                </Link>
            </div>
        </>
    );
};

const Nav = () => {
    const [isOpen, setIsOpen] = useState(false);
    const toggleNavbar = () => {
        setIsOpen(!isOpen);
    }

    return (
        <>
            <div className='ButtonHeaderContainer flex justify-around w-2/3'>
                <NavLinks />
            </div>
            <div className='md:hidden'>
                {/* You can add a button here to toggle the navbar if needed */}
            </div>
        </>
    );
}

export default Nav;
