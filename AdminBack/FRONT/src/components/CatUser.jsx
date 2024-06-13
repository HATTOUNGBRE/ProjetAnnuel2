import React from "react";
import { Link } from "react-router-dom";

const CatUser = () => {
    return (
        <div className="flex h-screen">
            <div className="w-2/3 bg-pcs-200 imageCat">
                <div className="flex flex-col justify-center items-center h-full">
                    <div className="focus:outline-none text-white bg-pcs-600 focus:ring-4 mb-10 focus:ring-pcs-400 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        <h1 className="text-3xl text-white font-bold">Qui êtes-vous ?</h1>
                    </div>
                    <div className="flex flex-col">
                        <Link to="/login?voyageur" className="mb-4 focus:outline-none text-white bg-pcs-300 hover:bg-pcs-400 focus:ring-4 focus:ring-pcs-400 font-medium rounded-lg text-sm px-20 py-2.5 me-2 mb-2 w-full dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Voyageurs</Link>
                        <Link to="/login?proprietaire" className="mb-4 focus:outline-none text-white text-center bg-pcs-300 hover:bg-pcs-400 focus:ring-4 focus:ring-pcs-400 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 w-full dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Propriétaire</Link>
                        <Link to="/login?prestataire" className="focus:outline-none text-center text-white bg-pcs-300 hover:bg-pcs-400 focus:ring-4 focus:ring-pcs-400 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 w-full dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">Prestataire</Link>
                    </div>
                </div>
            </div>
            <div className="w-1/3 bg-cover" style={{ backgroundImage: "url('/public/images/client.jpg')", backgroundPosition: 'center' }}></div>
        </div>
    );
}

export default CatUser;
