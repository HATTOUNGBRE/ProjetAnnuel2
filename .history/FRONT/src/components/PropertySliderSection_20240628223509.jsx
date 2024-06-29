import React from 'react';
import Slider from 'react-slick';

const PropertySliderSection = ({ title, sliderSettings }) => {
  return (
    <div className="mt-10 w-full">
      <h2 className="text-2xl font-semibold text-pcs-400 mb-6">{title}</h2>
      <Slider {...sliderSettings}>
        {[1, 2, 3, 4, 5].map(item => (
          <div key={item} className="p-4">
            <div className="bg-white shadow-lg rounded-lg overflow-hidden">
              <img src="http://localhost:8000/uploads/property_photos/default_appart.jpg" alt={`Appartement ${item}`} className="h-48 w-full object-cover" />
              <div className="p-4">
                <h3 className="text-xl font-semibold mb-2">Appartement {item}</h3>
                <p className="text-gray-600 mb-4">Description de l'appartement {item}</p>
                <div className="flex justify-between">
                  <button className="bg-pcs-250 hover:bg-pcs-200 text-white py-2 px-4 rounded-md">Détail +</button>
                  <button className="bg-pcs-400 text-white py-2 px-4 rounded-md">Louer</button>
                </div>
              </div>
            </div>
          </div>
        ))}
      </Slider>
    </div>
  );
};

export default PropertySliderSection;
