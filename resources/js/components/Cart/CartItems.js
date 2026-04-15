import React from 'react';

export default function CartItems({ items, onRemoveItem, onUpdateQuantity }) {
    return (
        <div className="cart-items-list">
            {items.map((item, index) => (
                <div key={item.pricing_id || index} className="cart-item">
                    <div className="cart-item-image">
                        <img src={item.cover_image} alt={item.game_name} />
                    </div>

                    <div className="cart-item-details">
                        <h3 className="game-name">{item.game_name}</h3>
                        
                        <div className="pricing-info">
                            {item.discount_percentage > 0 && (
                                <div className="discount-badge">
                                    <span className="discount-percent">-{item.discount_percentage}%</span>
                                </div>
                            )}
                            
                            <div className="price-section">
                                {item.discount_percentage > 0 && (
                                    <span className="original-price">${item.original_price.toFixed(2)}</span>
                                )}
                                <span className="current-price">${item.price.toFixed(2)}</span>
                            </div>
                        </div>
                    </div>

                    <div className="cart-item-quantity">
                        <label htmlFor={`qty-${item.pricing_id}`}>Quantity:</label>
                        <input
                            id={`qty-${item.pricing_id}`}
                            type="number"
                            min="1"
                            value={item.quantity || 1}
                            onChange={(e) => onUpdateQuantity(item.pricing_id, parseInt(e.target.value))}
                            className="quantity-input"
                        />
                    </div>

                    <div className="cart-item-total">
                        <span className="total-price">${(item.price * (item.quantity || 1)).toFixed(2)}</span>
                    </div>

                    <button
                        className="btn-remove"
                        onClick={() => onRemoveItem(item.pricing_id)}
                        title="Remove from cart"
                    >
                        Remove
                    </button>
                </div>
            ))}
        </div>
    );
}
