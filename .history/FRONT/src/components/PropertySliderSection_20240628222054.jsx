import React, { useEffect, useState } from 'react';
import Slider from 'react-slick';
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';

const PropertySliderSection = ({ title, apiUrl }) => {
  const [properties, setProperties] = useState([]);

  useEffect(() => {
    const fetchProperties = async () => {
      try {
        const response = await fetch(apiUrl);
        const data = await response.json();
        setProperties(data);
      } catch (error) {
        console.error('Error fetching properties:', error);
      }
    };

    fetchProperties();
  }, [apiUrl]);

  const sliderSettings = {
    dots: true,
    infinite: true,
    speed: 500,
    slidesToShow: 3,
    slidesToScroll: 1,
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: true,
          dots: true
        }
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  };

  return (
    <div className="mt-10 w-full">
      <h2 className="text-2xl font-semibold text-pcs-400 mb-6">{title}</h2>
      <Slider {...sliderSettings}>
        {properties.map(property => (
          <div key={property.id} className="p-4">
            <div className="bg-white shadow-lg rounded-lg overflow-hidden">
              <img src={`http://localhost:8000/uploads/property_photos/${property.image || 'default_appart.jpg'}`} alt={property.name} className="h-48 w-full object-cover" />
              <div className="p-4">
                <h3 className="text-xl font-semibold mb-2">{property.name}</h3>
                <p className="text-gray-600 mb-4">{property.description}</p>
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
