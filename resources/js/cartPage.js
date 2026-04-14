import React from 'react';
import ReactDOM from 'react-dom';
import CartPage from './components/CartPage';

const cartRoot = document.getElementById('cart-page-root');

if (cartRoot) {
    ReactDOM.render(<CartPage />, cartRoot);
}
