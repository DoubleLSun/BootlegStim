import React, { useState, useEffect } from 'react';
import CartItems from './Cart/CartItems';
import CartSummary from './Cart/CartSummary';
import '../../css/cart/cartPage.css';

export default function CartPage() {
    const [cartItems, setCartItems] = useState([]);
    const [totalPrice, setTotalPrice] = useState(0);
    const [totalItems, setTotalItems] = useState(0);
    const [loading, setLoading] = useState(false);
    const [initialized, setInitialized] = useState(false);

    useEffect(() => {
        loadCart();
    }, []);

    const applySnapshot = (snapshot) => {
        setCartItems(snapshot.cartItems || []);
        setTotalPrice(snapshot.totalPrice || 0);
        setTotalItems(snapshot.totalItems || 0);
    };

    const loadCart = async () => {
        setLoading(true);
        try {
            const response = await axios.get('/cart/data');
            applySnapshot(response.data);
        } catch (error) {
            console.error('Error loading cart data:', error);
        } finally {
            setInitialized(true);
            setLoading(false);
        }
    };

    const handleRemoveItem = async (pricingId) => {
        setLoading(true);
        try {
            const response = await axios.post('/cart/remove-item', {
                pricing_id: pricingId,
            });
            applySnapshot(response.data);
        } catch (error) {
            console.error('Error removing item:', error);
        } finally {
            setLoading(false);
        }
    };

    const handleClearCart = () => {
        if (window.confirm('Are you sure you want to remove all items from your cart?')) {
            setLoading(true);
            axios.post('/cart/clear', {})
                .then(response => {
                    applySnapshot(response.data);
                })
                .catch(error => console.error('Error clearing cart:', error))
                .finally(() => setLoading(false));
        }
    };

    const handleUpdateQuantity = (pricingId, newQuantity) => {
        axios.post('/cart/update-quantity', {
            pricing_id: pricingId,
            quantity: Math.max(1, newQuantity),
        })
            .then((response) => applySnapshot(response.data))
            .catch(error => console.error('Error updating quantity:', error));
    };

    if (!initialized && loading) {
        return (
            <div className="cart-page-container">
                <div className="cart-content-wrapper">
                    <div className="cart-items-section">
                        <div className="empty-cart">
                            <p>Loading your cart...</p>
                        </div>
                    </div>
                </div>
            </div>
        );
    }

    return (
        <div className="cart-page-container">
            <div className="cart-content-wrapper">
                <div className="cart-items-section">
                    <div className="cart-header">
                        <h1>Shopping Cart</h1>
                        <span className="item-count">({totalItems} item{totalItems !== 1 ? 's' : ''})</span>
                    </div>

                    {cartItems.length === 0 ? (
                        <div className="empty-cart">
                            <p>Your cart is empty</p>
                        </div>
                    ) : (
                        <>
                            <CartItems
                                items={cartItems}
                                onRemoveItem={handleRemoveItem}
                                onUpdateQuantity={handleUpdateQuantity}
                            />

                            <div className="cart-actions">
                                <a href="/games" className="btn btn-continue-shopping">
                                    ← Continue Shopping
                                </a>
                                <button
                                    className="btn btn-clear-all"
                                    onClick={handleClearCart}
                                    disabled={loading}
                                >
                                    {loading ? 'Removing...' : 'Remove All Items'}
                                </button>
                            </div>
                        </>
                    )}
                </div>

                <CartSummary
                    totalPrice={totalPrice}
                    totalItems={totalItems}
                    itemCount={cartItems.length}
                />
            </div>
        </div>
    );
}
