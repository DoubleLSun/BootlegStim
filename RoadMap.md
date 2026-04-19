## Plan: High-Coverage Functional + Integration Testing for BootlegStim

This plan turns your ideas into a risk-first test roadmap: secure the critical flows first (auth, ownership, payment integrity), then expand to CRUD, navigation, search, reviews, and optional future features. It is designed to maximize real defect detection, not just inflate coverage numbers.

**Steps**
1. Phase 1: Build test foundation
2. Add reusable fixtures/factories for users, games, pricing, carts, orders, and reviews.
3. Standardize database isolation for all feature tests.
4. Create helper methods for common states: authenticated user, purchased game in library, cart with promo and wallet session.
5. Phase 2: Critical security and ownership (highest priority)
6. Write tests proving users cannot edit other users’ profiles.
7. Write tests proving users cannot access other users’ library content.
8. Add regression tests for library page identity selection so authenticated user data is always used.
9. Add cross-user isolation tests for cart and checkout endpoints.
10. Phase 3: Auth/session flow coverage
11. Cover register/login/logout, invalid login, duplicate registration, and guest redirects.
12. Verify logout invalidates session and removes checkout-related session state.
13. Phase 4: Checkout and payment integration
14. Cover full purchase flow: cart → checkout → payment success.
15. Assert order creation, pivot inserts, user library updates, wallet deduction, and cart clearing.
16. Add failure-path tests for rollback behavior and data consistency.
17. Add promo-code tests (valid/invalid/stacking behavior and total calculation correctness).
18. Phase 5: CRUD and route navigation
19. Add route smoke tests for all public and protected routes with expected status codes.
20. Add admin CRUD tests for games and pricing once admin authorization contract is finalized.
21. Add featured-flag tests to ensure featured games appear correctly on store pages.
22. Phase 6: Reviews, search, and play-time lifecycle
23. Add review create/update/delete ownership tests and one-review-per-user-per-game tests.
24. Add review aggregate/rating summary tests.
25. Add search tests for exact, partial, case-insensitive, and no-result behavior.
26. Add play-start/play-stop tests for start/end timestamps and duration persistence once endpoints exist.
27. Phase 7: Optional/unimplemented features
28. Add skipped/spec tests for wishlist, dedicated search results page, and optional welcome page behavior to act as future acceptance contracts.
29. Phase 8: Coverage enforcement
30. Set and enforce minimum coverage thresholds in CI.
31. Add must-pass grouping for critical flows: auth, authorization, payment, ownership isolation.

**Relevant files**
- web.php — route protection and route-smoke expectations.
- AuthController.php — auth flow test targets.
- ProfileController.php — profile authorization and update behavior.
- LibraryController.php — ownership and library isolation logic.
- PaymentController.php — checkout/payment integrity tests.
- CartPageController.php — cart CRUD and cross-user access tests.
- StoreController.php — featured and search behavior.
- GamePageController.php — price display and review summary consistency.
- ExampleTest.php — replace with real feature suites.
- ExampleTest.php — replace with meaningful unit coverage.

**Test cases you can implement (prioritized list)**

1. Critical authorization and isolation
2. User cannot update another user profile.
3. Profile update requires authentication.
4. User can view own library only.
5. User cannot open another user’s library entry detail.
6. Cart endpoints return only authenticated user cart items.
7. Checkout endpoints reject unauthenticated requests.
8. Library page reflects authenticated user data, not first database user.

9. Authentication and session
10. User can register successfully.
11. Duplicate email registration is rejected.
12. User can login with valid credentials.
13. Invalid credentials are rejected.
14. Logout invalidates authentication.
15. Guest is redirected from protected routes.
16. Promo and wallet session state is cleared/handled correctly on logout.

17. Cart and checkout functional flow
18. User can add game to cart.
19. User can remove a cart item.
20. User can clear cart.
21. User can update cart quantity.
22. Cart subtotal/total is calculated correctly.
23. Valid promo applies correct discount.
24. Invalid promo is rejected without corrupting totals.
25. Wallet toggle applies capped amount and updates final total correctly.

26. Payment integration and consistency
27. Successful payment creates order record.
28. Successful payment writes order-games pivot rows.
29. Purchased games are attached to user library.
30. Purchased library pivot starts with expected values.
31. Cart is cleared after successful payment.
32. Payment failure rolls back order/pivot/library changes.
33. Payment failure preserves cart contents.
34. Wallet deduction is correct and consistent with final charged amount.
35. Order reference uniqueness is enforced.

36. Admin/game/price CRUD
37. Admin can create game.
38. Admin can update game.
39. Admin can delete game.
40. Non-admin cannot access admin game CRUD routes.
41. Admin can create game price.
42. Admin can update game price.
43. Admin can delete game price.
44. Non-admin cannot access game price admin routes.
45. Toggling featured flag changes store featured listing as expected.

46. Store and navigation
47. Public can open home/store/game detail pages.
48. Search finds exact title.
49. Search finds partial title.
50. Search is case-insensitive.
51. Search with no match returns empty state.
52. Route smoke test for all main nav links with expected status per role.

53. Reviews
54. Authenticated owner can create review for owned game.
55. User cannot review game they do not own.
56. User can update own review only.
57. User cannot update/delete another user review.
58. One review per user per game constraint enforced.
59. Review aggregate label logic (positive/mixed/negative buckets) behaves correctly.

60. Play-time lifecycle
61. Play start action records start timestamp.
62. Play stop action records end timestamp.
63. Duration is calculated and persisted correctly.
64. User cannot modify play-time records for games they do not own.

65. Security-focused integration checks
66. CSRF-protected form endpoints reject missing/invalid tokens.
67. Sensitive actions require authenticated session.
68. Input validation blocks malformed payment/profile payloads.
69. Session fixation/regeneration behavior on login/logout is validated.

**Verification**
1. Run full feature suite and ensure all critical tests pass first.
2. Use database assertions on orders, pivots, carts, profiles, and reviews for each scenario.
3. Run route smoke suite for guest/authenticated/admin role expectations.
4. Track coverage and enforce threshold after critical suite stabilizes.
