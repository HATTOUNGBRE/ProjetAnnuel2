import React from 'react'
import ReactDOM from 'react-dom/client'
import { loadStripe } from '@stripe/stripe-js'
import { Elements } from '@stripe/react-stripe-js'
import App from './App.jsx'
import './App.css'
import { AuthProvider } from './components/AuthContext'
import {
  BrowserRouter
} from "react-router-dom"

const stripePromise = loadStripe('pk_test_51PZbBZRs7Q74dLwZKy4J74eVvc2jbRD3U7JegkmJMrkeJMYtcCv9NOWiKpihXXFEanX7ZJu8PCDPWtqVAZVmPtcD00Eq2HsJQ4');

ReactDOM.createRoot(document.getElementById('root')).render(
  <React.StrictMode>
    <BrowserRouter>
      <AuthProvider>
        <Elements stripe={stripePromise}>
          <App />
        </Elements>
      </AuthProvider>
    </BrowserRouter>
  </React.StrictMode>,
)
