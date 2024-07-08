import React from 'react'
import ReactDOM from 'react-dom/client'
import App from './App.jsx'
import './App.css'
import { AuthProvider } from './components/AuthContext';
import {
  BrowserRouter,
  Route,
  Link
} from "react-router-dom";

const stripePromise = loadStripe('pk_test_51PZbBZRs7Q74dLwZKy4J74eVvc2jbRD3U7JegkmJMrkeJMYtcCv9NOWiKpihXXFEanX7ZJu8PCDPWtqVAZVmPtcD00Eq2HsJQ4');

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <AuthProvider>
    <App />
    </AuthProvider>
  </React.StrictMode>,
)
