## Summary
<!-- brief summary of the repository -->
BootlegStim is a web application built with Laravel and Reactjs that serves as a game store platform. It allows users to browse and purchase games, manage their profiles, and view their game libraries. The application includes features such as user authentication, shopping cart functionality, payment processing, and an admin panel for managing games and prices.

## Architecture
<!-- short summary of the architecture -->
- 'app\Http\Controllers\' - MVC controllers for handling logic such as calculation, database interaction, and request validation for each route.
- 'app\Models\' - Eloquent models representing database tables and relationships.
- 'resources\views\' - Blade templates for rendering HTML views for the frontend.
- 'resources\js\' - JavaScript files for Reactjs frontend interactivity components and axios requests.
- 'resources\css\' - CSS files for styling the frontend views, separated by each individual pages.
- 'routes\web.php' - Route definitions mapping URLs to controller actions and applying middleware for authentication and authorization.
- 'routes\api.php' - Route definitions for API endpoints, if applicable.
- 'tests\Feature\' - Feature tests for testing the behavior of the application from the user's perspective.
- 'tests\Unit\' - Unit tests for testing individual components and logic in isolation.


## Terminology
<!-- list of domain specific terms with their explanation -->

## Task planning and problem-solving
<!-- the most important problem-solving guidelines -->
<!-- e.g. "plan the task before writing any code" -->
- Before writing any code, check if existing code can be reused or extended to implement the new feature or fix the bug. Avoid duplicating code if possible.
- Testing: Write feature tests to cover all main functions and buttons within each views pages

## Coding guidelines
<!-- the most important coding guidelines -->
- MVC architecture: Follow the Model-View-Controller pattern to separate concerns and organize code effectively.
- Eloquent ORM: Use Laravel's Eloquent ORM for database interactions to leverage its powerful features
- Blade templating: Use Blade templates for rendering views and avoid mixing PHP logic with HTML.
- Session management: Use Laravel's built-in session management for handling user authentication and state.
- Validation: Use Laravel's validation features to validate user input and ensure data integrity.
-  css and js organization: Separate CSS and JavaScript files in resources folder for both css and js directory, and organize them by pages or components for better maintainability.
- Testing: Write comprehensive feature tests to cover all main functions and buttons within each views pages, and unit tests for critical logic and components.
- css style: use distinctive class names for each page and component to avoid style conflicts, and follow a consistent naming convention for CSS classes.
- css theme: use consistent color scheme and typography across application.
