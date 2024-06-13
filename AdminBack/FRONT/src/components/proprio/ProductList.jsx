import React, { useState, useEffect, useContext } from 'react';
import AuthContext from '../AuthContext';
import EditProductModal from './EditProduct'; // Assurez-vous que le chemin est correct

const ProductList = ({ onDelete }) => {
    const { userId } = useContext(AuthContext);
    const [products, setProducts] = useState([]);
    const [categories, setCategories] = useState([]);
    const [editingProduct, setEditingProduct] = useState(null);

    const fetchProducts = async () => {
        try {
            const response = await fetch(`http://localhost:8000/api/products/${userId}`);
            const data = await response.json();
            setProducts(data);
        } catch (error) {
            console.error('Error fetching products:', error);
        }
    };

    const fetchCategories = async () => {
        try {
            const response = await fetch('http://localhost:8000/api/categories');
            const data = await response.json();
            setCategories(data);
        } catch (error) {
            console.error('Error fetching categories:', error);
        }
    };

    useEffect(() => {
        fetchProducts();
        fetchCategories();
    }, [userId]);

    const handleDelete = async (productId) => {
        try {
            const response = await fetch(`http://localhost:8000/api/products/${productId}`, {
                method: 'DELETE',
            });

            if (response.ok) {
                setProducts(products.filter(product => product.id !== productId));
                onDelete();
            } else {
                console.error('Failed to delete the product');
            }
        } catch (error) {
            console.error('Error deleting the product:', error);
        }
    };

    const handleSave = (updatedProduct) => {
        setProducts(products.map(product => product.id === updatedProduct.id ? updatedProduct : product));
    };

    return (
        <div className="mt-6">
            <h3 className="text-2xl font-semibold text-gray-800 mb-4">Liste des Propriétés</h3>
            <div className="overflow-x-auto">
                <table className="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th className="py-2 text-left px-4 bg-gray-100">Nom</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Description</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Prix</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Catégorie</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Statut</th>
                            <th className="py-2 text-left px-4 bg-gray-100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        {products.map((product) => (
                            <tr key={product.id}>
                                <td className="py-2 px-4 border-b">{product.name}</td>
                                <td className="py-2 px-4 border-b">{product.description}</td>
                                <td className="py-2 px-4 border-b">{product.price}</td>
                                <td className="py-2 px-4 border-b">{product.category ? product.category.name : 'N/A'}</td>
                                <td className="py-2 px-4 border-b">{product.active ? 'Active' : 'Inactive'}</td>
                                <td className="py-2 px-4 border-b space-x-2">
                                    <button
                                        className="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition"
                                        onClick={() => setEditingProduct(product)}
                                    >
                                        Modifier
                                    </button>
                                    <button
                                        onClick={() => handleDelete(product.id)}
                                        className="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 transition"
                                    >
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                        ))}
                    </tbody>
                </table>
            </div>
            {editingProduct && (
                <EditProductModal
                    product={editingProduct}
                    categories={categories}
                    onClose={() => setEditingProduct(null)}
                    onSave={handleSave}
                />
            )}
        </div>
    );
};

export default ProductList;
