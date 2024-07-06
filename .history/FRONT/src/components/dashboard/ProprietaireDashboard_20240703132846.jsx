import React from 'react';
import Calendar from './Calendar';
const ProprietaireDashboard = () => {
    return (
        <div className="flex h-screen bg-pcs-100">
            {/* Sidebar */}
            <div className="w-64 bg-pcs-250 shadow-md">
                {/* Sidebar content */}
            </div>

            {/* Main content */}
            <div className="flex-1 p-6">
                <h2 className="text-3xl font-semibold text-gray-800 mb-2">📆 Vos Chiffres</h2>

                {/* Cards section */}
                {/* Existing cards section */}

                {/* Calendar section */}
                <div className="mt-6">
                    <h2 className="text-2xl font-semibold text-gray-800 mb-4">Disponibilités</h2>
                    <Calendar />
                </div>

                {/* Properties list section */}
                {/* Existing properties list section */}
            </div>
        </div>
    );
};

export default ProprietaireDashboard;
