import React from 'react';
import ReactDOM from 'react-dom';
import CartPage from './components/CartPage';

const cartRoot = document.getElementById('cart-page-root');

if (cartRoot) {
    // Render the CartPage component into the cartRoot element
    ReactDOM.render(<CartPage />, cartRoot);
}
