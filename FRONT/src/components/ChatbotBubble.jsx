import React, { useState } from 'react';
import { FaComments, FaTimes } from 'react-icons/fa';

const ChatbotBubble = () => {
    const [isOpen, setIsOpen] = useState(false);
    const [messages, setMessages] = useState([]);
    const [input, setInput] = useState('');

    const toggleChat = () => {
        setIsOpen(!isOpen);
        if (!isOpen) {
            startConversation();
        }
    };

    const handleSendMessage = (message) => {
        if (message.trim()) {
            setMessages([...messages, { text: message, isUser: true }]);
            handleBotResponse(message);
            setInput('');
        }
    };

    const handleBotResponse = (input) => {
        const lowerInput = input.toLowerCase();
        let response = '';
        let options = [];

        if (lowerInput.includes('bonjour') || lowerInput.includes('salut')) {
            response = 'Bonjour ! Comment puis-je vous aider aujourd\'hui ?';
            options = ['Ajouter une propriété', 'Voir les propriétés', 'S\'inscrire'];
        } else if (lowerInput.includes('propriété')) {
            response = 'Souhaitez-vous ajouter ou voir des propriétés ?';
            options = ['Ajouter une propriété', 'Voir les propriétés'];
        } else if (lowerInput.includes('ajouter une propriété')) {
            response = 'Vous pouvez ajouter une propriété en allant sur la page Ajouter une propriété.';
            window.location.href = '/add-property'; // Ajustez le chemin selon vos besoins
        } else if (lowerInput.includes('voir les propriétés')) {
            response = 'Vous pouvez voir les propriétés en allant sur la page Propriétés.';
            window.location.href = '/properties'; // Ajustez le chemin selon vos besoins
        } else if (lowerInput.includes('s\'inscrire')) {
            response = 'Vous pouvez vous inscrire en allant sur la page Inscription.';
            window.location.href = '/register'; // Ajustez le chemin selon vos besoins
        } else {
            response = 'Désolé, je n\'ai pas compris. Pouvez-vous reformuler ?';
            options = ['Ajouter une propriété', 'Voir les propriétés', 'S\'inscrire'];
        }

        setMessages(prevMessages => [...prevMessages, { text: response, isUser: false, options }]);
    };

    const startConversation = () => {
        handleBotResponse('bonjour');
    };

    return (
        <div className="fixed bottom-4 right-4 z-50">
            <div className={`bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300 ${isOpen ? 'w-80 h-96' : 'w-12 h-12'}`}>
                {isOpen ? (
                    <>
                        <div className="flex justify-between items-center bg-gray-800 text-white p-2">
                            <h2 className="text-lg">Chatbot</h2>
                            <button onClick={toggleChat} className="text-xl"><FaTimes /></button>
                        </div>
                        <div className="flex flex-col p-4 h-full overflow-y-auto">
                            <div className="flex-1 mb-4">
                                {messages.map((message, index) => (
                                    <div key={index} className={`my-2 p-2 rounded-lg ${message.isUser ? 'bg-gray-200 text-right' : 'bg-gray-300 text-left'}`}>
                                        {message.text}
                                        {message.options && (
                                            <div className="mt-2 flex flex-wrap">
                                                {message.options.map((option, i) => (
                                                    <button
                                                        key={i}
                                                        onClick={() => handleSendMessage(option)}
                                                        className="bg-blue-500 text-white py-1 px-2 rounded-lg m-1"
                                                    >
                                                        {option}
                                                    </button>
                                                ))}
                                            </div>
                                        )}
                                    </div>
                                ))}
                            </div>
                            <div className="flex mb-10">
                                <input
                                    type="text"
                                    value={input}
                                    onChange={(e) => setInput(e.target.value)}
                                    className="flex-1 p-2 border rounded-l-lg focus:outline-none"
                                    placeholder="Tapez un message..."
                                    onKeyPress={(e) => e.key === 'Enter' && handleSendMessage(input)}
                                />
                                <button onClick={() => handleSendMessage(input)} className="bg-gray-800 text-white p-2 rounded-r-lg">
                                    Envoyer
                                </button>
                            </div>
                        </div>
                    </>
                ) : (
                    <button onClick={toggleChat} className="w-full h-full flex items-center justify-center bg-gray-800 text-white rounded-full text-2xl">
                        <FaComments />
                    </button>
                )}
            </div>
        </div>
    );
};

export default ChatbotBubble;
