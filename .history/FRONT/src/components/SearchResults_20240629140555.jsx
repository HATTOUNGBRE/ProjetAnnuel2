const SearchResults = ({ properties }) => (
    <div className="mt-10 w-full">
      <h2 className="text-2xl font-semibold text-pcs-400 mb-6">Résultats de la Recherche</h2>
      {properties.length > 0 ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          {properties.map(property => (
            <PropertyRow key={property.id} property={property} />
          ))}
        </div>
      ) : (
        <p className="text-gray-600">Pas de résultat pour cette recherche</p>
      )}
    </div>
  );
  