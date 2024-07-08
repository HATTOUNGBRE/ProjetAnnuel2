import React, { useState } from 'react';
import { FaComments, FaTimes } from 'react-icons/fa';

const ChatbotBubble = () => {
    const [isOpen, setIsOpen] = useState(false);
    const [messages, setMessages] = useState([]);
    const [input, setInput] = useState('');

    const toggleChat = () => {
        setIsOpen(!isOpen);
    };

    const handleSendMessage = () => {
        if (input.trim()) {
            setMessages([...messages, { text: input, isUser: true }]);
            setInput('');
        }
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
                                    </div>
                                ))}
                            </div>
                            <div className="flex mb-10">
                                <input
                                    type="text"
                                    value={input}
                                    onChange={(e) => setInput(e.target.value)}
                                    className="flex-1 p-2 border rounded-l-lg focus:outline-none"
                                    placeholder="Type a message..."
                                />
                                <button onClick={handleSendMessage} className="bg-gray-800 text-white p-2 rounded-r-lg">
                                    Send
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
