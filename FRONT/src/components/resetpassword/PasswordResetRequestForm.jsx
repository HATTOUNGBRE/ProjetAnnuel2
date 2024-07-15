import React, { useState } from 'react';
import axios from 'axios';

const PasswordResetRequestForm = () => {
  const [email, setEmail] = useState('');
  const [message, setMessage] = useState('');

  const handleFormSubmit = async (e) => {
    e.preventDefault();

    try {
      const response = await axios.post(
        '/api/request-password-reset',
        { email },
        {
          headers: {
            'Content-Type': 'application/json',
          },
        }
      );

      setMessage(response.data.message);
    } catch (error) {
      if (error.response) {
        setMessage(error.response.data.message);
      } else {
        setMessage('An error occurred. Please try again later.');
      }
    }
  };

  return (
    <div>
      <h2>Request Password Reset</h2>
      <form onSubmit={handleFormSubmit}>
        <label htmlFor="email">Email:</label>
        <input
          type="email"
          id="email"
          name="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />
        <br />
        <button type="submit">Reset Password</button>
      </form>
      {message && <p>{message}</p>}
    </div>
  );
};

export default PasswordResetRequestForm;
