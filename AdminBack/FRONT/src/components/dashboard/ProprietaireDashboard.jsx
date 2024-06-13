import React, { useContext } from 'react';
import { FaHome, FaClipboardList, FaUsers, FaCog, FaPlus, FaSync } from 'react-icons/fa';
import { Link } from 'react-router-dom';
import AuthContext from '../AuthContext';
import ProductList from '../proprio/ProductList';
const ProprietaireDashboard = () => {
    const { userId } = useContext(AuthContext);

    const handleReload = () => {
        // Force the ProductList component to reload the products
        setReloadKey(prevKey => prevKey + 1);
    };

    return (
        <div className="flex h-screen bg-pcs-100">
            {/* Sidebar */}
            <div className="w-64 bg-pcs-250 shadow-md">
                <div className="p-6">
                    <h1 className="text-3xl font-semibold text-gray-800">Dashboard Propriétaire</h1>
                    <nav className="mt-10">
                        <Link to="/" className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700">
                            <FaHome className="w-5 h-5" />
                            <span className="mx-4 font-medium">Home</span>
                        </Link>
                        <a className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700" href="#">
                            <FaClipboardList className="w-5 h-5" />
                            <span className="mx-4 font-medium">Listings</span>
                        </a>
                        <a className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700" href="#">
                            <FaUsers className="w-5 h-5" />
                            <span className="mx-4 font-medium">Tenants</span>
                        </a>
                        <a className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700" href="#">
                            <FaCog className="w-5 h-5" />
                            <span className="mx-4 font-medium">Settings</span>
                        </a>
                        <Link to="/add-property" className="flex items-center p-2 mt-4 text-pcs-100 transition-colors duration-200 transform rounded-md hover:bg-gray-200 hover:text-gray-700">
                            <FaPlus className="w-5 h-5" />
                            <span className="mx-4 font-medium">Ajouter une propriété</span>
                        </Link>
                    </nav>
                </div>
            </div>

            {/* Main content */}
            <div className="flex-1 p-6">
                <h2 className="text-3xl font-semibold text-gray-800 mb-2">📆 Vos Chiffres</h2>

                {/* Cards section */}
                <div className="mt-6">
                    <div className="grid gap-6 mb-8 md:grid-cols-2 lg:grid-cols-4">
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Total Properties</h4>
                            <p className="text-gray-600">12</p>
                            <h6 className="text-gray-600">+2 from last month</h6>
                        </div>
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Occupied</h4>
                            <p className="text-gray-600">8</p>
                        </div>
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Vacant</h4>
                            <p className="text-gray-600">4</p>
                        </div>
                        <div className="min-w-0 p-4 bg-white rounded-lg shadow-xs">
                            <h4 className="mb-4 font-semibold text-gray-800">Earnings</h4>
                            <p className="text-gray-600">$24,000</p>
                        </div>
                    </div>
                </div>

                {/* Products list section */}
                <div className="mt-6">
                   
                    <ProductList onDelete={handleReload} />
                </div>
            </div>
        </div>
    );
};

export default ProprietaireDashboard;
