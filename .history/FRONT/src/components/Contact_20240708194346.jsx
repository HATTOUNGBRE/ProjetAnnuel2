import React, { useState } from 'react';

function Contact() {
  const [role, setRole] = useState('');
  const [questions, setQuestions] = useState([]);
  const [selectedQuestion, setSelectedQuestion] = useState('');
  const [formData, setFormData] = useState({
    name: '',
    surname: '',
    email: '',
    role: '',
    question: '',
    message: ''
  });
  const [successMessage, setSuccessMessage] = useState('');
  const [errorMessage, setErrorMessage] = useState('');

  const handleRoleChange = (event) => {
    const selectedRole = event.target.value;
    setRole(selectedRole);

    const roleQuestions = {
      voyageur: [
        'Comment réserver une propriété ?',
        'Quelles sont les modalités de paiement ?',
        'Comment annuler une réservation ?',
      ],
      proprietaire: [
        'Comment ajouter une nouvelle propriété ?',
        'Comment gérer mes réservations ?',
        'Quelles sont les commissions applicables ?',
      ],
      prestataire: [
        'Comment proposer mes services ?',
        'Quelles sont les conditions pour être prestataire ?',
        'Comment suivre mes missions ?',
      ],
    };

    setQuestions(roleQuestions[selectedRole] || []);
    setSelectedQuestion(''); // Reset selected question when role changes

    setFormData({ ...formData, role: selectedRole, question: '' });
  };

  const handleQuestionChange = (event) => {
    setSelectedQuestion(event.target.value);
    setFormData({ ...formData, question: event.target.value });
  };

  const handleInputChange = (event) => {
    const { name, value } = event.target;
    setFormData({ ...formData, [name]: value });
  };

  const handleSubmit = async (event) => {
    event.preventDefault();

    try {
      const response = await fetch('http://localhost:8000/api/contact', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(formData),
      });

      if (response.ok) {
        setSuccessMessage('Votre message a été envoyé avec succès.');
        setFormData({
          name: '',
          surname: '',
          email: '',
          role: '',
          question: '',
          message: ''
        });
        setRole('');
        setQuestions([]);
        setSelectedQuestion('');
      } else {
        throw new Error('Une erreur est survenue lors de l\'envoi du message.');
      }
    } catch (error) {
      setErrorMessage(error.message);
    }
  };

  const roleThemes = {
    voyageur: {
      title: "Besoin d'aide, Voyageur ?",
      backgroundColor: 'bg-pcs-400',
      backgroundColor2: 'bg-pcs-300',
      buttonColor: 'bg-pcs-300 hover:bg-pcs-350',
    },
    proprietaire: {
      title: "Besoin d'aide, Propriétaire ?",
      backgroundColor: 'bg-pcs-700',
      backgroundColor2: 'bg-pcs-600',
      buttonColor: 'bg-pcs-600 hover:bg-pcs-650',
    },
    prestataire: {
      title: "Besoin d'aide, Prestataire ?",
      backgroundColor: 'bg-pcs-550',
      backgroundColor2: 'bg-pcs-580',
      buttonColor: 'bg-pcs-580 hover:bg-pcs-570',
    },
  };

  const theme = roleThemes[role] || {
    title: "Besoin d'aide ?",
    backgroundColor: 'bg-gray-700',
    buttonColor: 'bg-gray-600 hover:bg-gray-500',
  };

  return (
    <div className="flex h-screen">
      <div className={`w-1/2 ${theme.backgroundColor} flex items-center justify-center`}>
        <div className="text-white text-center px-6 py-8">
          <div className={`${theme.backgroundColor2} text-white rounded-lg p-3 mb-10 pt-3 pb-1 m-3 font-semibold`}>
            <h1 className="text-4xl font-bold mb-4">{theme.title}</h1>
            <p className="text-lg font-normal mb-8">
              Vous êtes un voyageur, un propriétaire ou un prestataire ?<br />
              Vous avez une question ?
            </p>
          </div>
          {successMessage && (
            <div className="bg-green-200 text-green-800 p-3 mb-4 rounded-lg">
              {successMessage}
            </div>
          )}
          {errorMessage && (
            <div className="bg-red-200 text-red-800 p-3 mb-4 rounded-lg">
              {errorMessage}
            </div>
          )}
          <form className="flex flex-col w-full max-w-md mx-auto" onSubmit={handleSubmit}>
            <input
              type="text"
              name="name"
              placeholder="Nom"
              value={formData.name}
              onChange={handleInputChange}
              className="border-2 border-gray-300 p-3 mb-4 rounded-lg"
              required
            />
            <input
              type="text"
              name="surname"
              placeholder="Prénom"
              value={formData.surname}
              onChange={handleInputChange}
              className="border-2 border-gray-300 p-3 mb-4 rounded-lg"
              required
            />
            <input
              type="email"
              name="email"
              placeholder="Email"
              value={formData.email}
              onChange={handleInputChange}
              className="border-2 border-gray-300 p-3 mb-4 rounded-lg"
              required
            />
            <select
              name="role"
              className="border-2 border-gray-300 p-3 mb-4 rounded-lg"
              value={role}
              onChange={handleRoleChange}
              required
            >
              <option value="">Vous êtes...</option>
              <option value="voyageur">Voyageur</option>
              <option value="proprietaire">Propriétaire</option>
              <option value="prestataire">Prestataire</option>
            </select>
            {questions.length > 0 && (
              <select
                name="question"
                className="border-2 border-gray-300 p-3 mb-4 rounded-lg"
                value={selectedQuestion}
                onChange={handleQuestionChange}
                required
              >
                <option value="">Choisissez une question...</option>
                {questions.map((question, index) => (
                  <option key={index} value={question}>{question}</option>
                ))}
              </select>
            )}
            <textarea
              name="message"
              placeholder="Message"
              value={formData.message}
              onChange={handleInputChange}
              className="border-2 border-gray-300 p-3 mb-4 rounded-lg h-32"
              required
            />
            <button
              type="submit"
              className={`${theme.buttonColor} text-white rounded-lg p-3 font-semibold transition duration-300`}
            >
              Envoyer
            </button>
          </form>
        </div>
      </div>
      <div
        className="w-1/2 bg-cover bg-center"
        style={{ backgroundImage: "url('/images/girl.jpg')" }}
      ></div>
    </div>
  );
}

export default Contact;
