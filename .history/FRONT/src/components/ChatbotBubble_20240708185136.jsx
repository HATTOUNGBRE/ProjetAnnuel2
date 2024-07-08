import React, { useState } from 'react';
import { FaComments, FaTimes } from 'react-icons/fa';

const ChatbotBubble = () => {
    const [isOpen, setIsOpen] = useState(false);

    const toggleChat = () => {
        setIsOpen(!isOpen);
    };

    return (
        <div className="fixed bottom-4 right-4 z-50">
            <div className={`bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-300 ${isOpen ? 'w-72 h-96' : 'w-12 h-12'}`}>
                {isOpen ? (
                    <>
                        <div className="flex justify-between items-center bg-blue-500 text-white p-2">
                            <h2 className="text-lg">Chatbot</h2>
                            <button onClick={toggleChat} className="text-xl"><FaTimes /></button>
                        </div>
                        <div className="p-4 h-full overflow-y-auto">
                            <p className="mb-4">Bonjour! Comment puis-je vous aider aujourd'hui?</p>
                            {/* Ajoutez ici un champ de texte et un bouton pour envoyer des messages */}
                        </div>
                    </>
                ) : (
                    <button onClick={toggleChat} className="w-full h-full flex items-center justify-center bg-blue-500 text-white rounded-full text-2xl">
                        <FaComments />
                    </button>
                )}
            </div>
        </div>
    );
};

export default ChatbotBubble;
