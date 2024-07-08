import React from 'react';

function AboutUs() {
  return (
    <div className="bg-gray-50 min-h-screen py-12">
      <div className="container mx-auto px-4">
        <div className="bg-white shadow-md rounded-lg p-8">
          <h1 className="text-4xl font-bold text-center text-pcs-400 mb-8">Qui sommes-nous ?</h1>
          <p className="text-lg text-gray-700 mb-4">
            Paris Caretaker Services (PCS) est une chaîne de conciergeries immobilières assurant une offre de gestion locative saisonnière (type AirBnB), créée à Paris en 2018. La société a connu une croissance rapide depuis sa création, principalement en raison de la qualité de son accueil et de la richesse de ses prestations.
          </p>
          <p className="text-lg text-gray-700 mb-4">
            Elle offre des services allant de la gestion des réservations jusqu'à l’entretien du bien immobilier pour le compte du propriétaire, en passant par l’accueil des clients. Ainsi, de nombreux bailleurs peuvent louer leurs logements sans avoir à gérer les différentes charges inhérentes à la location.
          </p>
          <p className="text-lg text-gray-700 mb-4">
            Obtenir un service de conciergerie chez PCS est très simple : il suffit de se rendre sur sa plateforme Web et de demander une simulation de devis et de gains potentiels.
          </p>
          <h2 className="text-3xl font-semibold text-pcs-400 mb-6 mt-8">Nos services</h2>
          <ul className="list-disc list-inside text-lg text-gray-700 mb-4">
            <li>Check-in et check-out des clients</li>
            <li>Nettoyage du logement</li>
            <li>Publication des annonces avec mise en valeur du logement par le biais de photos de qualité et communication avec les voyageurs</li>
            <li>Contact (le service client est accessible 24h/24 et 7j/7)</li>
            <li>Optimisation des tarifs de location</li>
            <li>Fourniture de linge de maison</li>
            <li>Travaux d’entretien, petites réparations...</li>
            <li>Petite maintenance et réparations ponctuelles</li>
            <li>Changement des ampoules</li>
            <li>Petits travaux de plomberie</li>
            <li>Réparation du mobilier</li>
            <li>Transport de et vers aéroport</li>
          </ul>
          <p className="text-lg text-gray-700 mb-4">
            Le tarif d'abonnement annuel est de 100 euros payé par le propriétaire bailleur (tous les tarifs sont révisables). À cela s’ajoutent d’autres frais. Le plus souvent, il s’agit de frais fixes (coûts du service d’entretien...) et de frais logistiques. Cependant, de nombreuses sommes sont réglées par les locataires.
          </p>
          <p className="text-lg text-gray-700 mb-4">
            Pour assurer toutes ces missions, PCS dispose d’un catalogue varié de prestataires (chauffeurs, agents d’entretien, agents immobiliers, livreurs, blanchisseurs, photographes...), prestataires extérieurs qu’elle choisit avec soin.
          </p>
          <h2 className="text-3xl font-semibold text-pcs-400 mb-6 mt-8">Nos agences</h2>
          <p className="text-lg text-gray-700 mb-4">
            Une première agence a été créée au 23, rue Montorgueil, dans le 2ème arrondissement, en 2018 ; le succès a été tel que Paris Caretaker Services a depuis ouvert depuis six autres espaces dans les 1er, 3ème, 4ème, 5ème, 6ème et 18ème arrondissements. Chacun d’entre eux dispose de bureaux d’accueil et de boîtes à clefs.
          </p>
          <p className="text-lg text-gray-700 mb-4">
            Au vu de son succès, elle a également ouvert des annexes à Troyes, Nice et Biarritz.
          </p>
        </div>
      </div>
    </div>
  );
}

export default AboutUs;
