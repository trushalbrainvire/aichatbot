import { StrictMode } from 'react'
import { createRoot } from 'react-dom/client'
import App from './App.jsx'

createRoot(document.querySelector('bv-ai-chatbox')).render(
  <StrictMode>
    <App />
  </StrictMode>,
)
