import React, { useState, useEffect } from 'react';

export default function CartSummary({ totalPrice, totalItems, itemCount }) {
    const [isSticky, setIsSticky] = useState(false);

    // Handle sticky positioning on scroll
    useEffect(() => {
        const handleScroll = () => {
            const summaryElement = document.querySelector('.cart-summary-container');
            if (summaryElement) {
                const rect = summaryElement.getBoundingClientRect();
                setIsSticky(rect.top < 0);
            }
        };

        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, []);

    return (
        <div className={`cart-summary-container ${isSticky ? 'sticky' : ''}`}>
            <div className="cart-summary-box">
                <div className="summary-section">
                    <h2>Order Summary</h2>
                </div>

                <div className="summary-divider"></div>

                <div className="summary-details">
                    <div className="summary-row">
                        <span className="summary-label">Items ({itemCount}):</span>
                        <span className="summary-value">${totalPrice.toFixed(2)}</span>
                    </div>

                    <div className="summary-row">
                        <span className="summary-label">Subtotal:</span>
                        <span className="summary-value">${totalPrice.toFixed(2)}</span>
                    </div>
                </div>

                <div className="summary-divider"></div>

                <div className="summary-total">
                    <span className="total-label">Total:</span>
                    <span className="total-amount">${totalPrice.toFixed(2)}</span>
                </div>

                <div className="summary-divider"></div>

                <button className="btn btn-proceed-to-payment">
                    Proceed to Payment
                </button>

                <div className="summary-note">
                    <small>Prices shown in USD</small>
                </div>
            </div>
        </div>
    );
}
