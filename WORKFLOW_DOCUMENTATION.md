# Rick and Morty Portal - Complete File Workflow Documentation 🛸

## Project Overview
A Symfony 7.3 web application that allows users to browse Rick and Morty characters with authentication and favorites functionality. Built with PHP 8.2+, SQLite database, and Twig templating.

---

## Core Application Architecture

### 1. **Application Entry Point**

#### `public/index.php`
- **Purpose**: Main entry point for all HTTP requests
- **Workflow**: 
  1. Loads Symfony's bootstrapping system
  2. Creates and runs the Kernel
  3. Handles all incoming requests and routes them appropriately
- **Details**: Standard Symfony front controller pattern

#### `src/Kernel.php`
- **Purpose**: Application kernel that configures the Symfony framework
- **Workflow**: 
  1. Extends Symfony's BaseKernel
  2. Uses MicroKernelTrait for simplified configuration
  3. Loads bundles, configuration, and routing
- **Details**: Minimal configuration using Symfony's conventions

---

## 2. **Configuration Layer**

### Core Configuration Files

#### `config/services.yaml`
- **Purpose**: Main service container configuration
- **Workflow**: 
  1. Defines default service configuration (autowiring enabled)
  2. Registers all classes in `src/` as services
  3. Provides dependency injection container setup
- **Details**: Uses Symfony's auto-configuration for seamless service registration

#### `config/routes.yaml`
- **Purpose**: Main routing configuration
- **Workflow**: 
  1. Loads all controllers from `src/Controller/` directory
  2. Uses PHP attributes for route definition
  3. Maps URLs to controller actions
- **Details**: Attribute-based routing system

#### `config/packages/security.yaml`
- **Purpose**: Authentication and authorization configuration
- **Workflow**: 
  1. **Password Hashing**: Configures automatic password hashing
  2. **User Provider**: Links to User entity for authentication
  3. **Firewall Configuration**:
     - Development routes bypass security
     - Main firewall handles login/logout
     - Form login redirects to character list on success
  4. **Access Control**: 
     - Public: `/`, `/registration`
     - Protected: `/characters/{id}`, `/user/characters`
- **Details**: Session-based authentication with CSRF protection

#### `config/packages/doctrine.yaml`
- **Purpose**: Database ORM configuration
- **Workflow**: 
  1. Configures SQLite as database
  2. Sets up entity mapping
  3. Handles database connection and migrations
- **Details**: Uses SQLite for simplicity and portability

---

## 3. **Database Layer**

### Entities (Data Models)

#### `src/Entity/User.php`
- **Purpose**: User authentication and account management
- **Workflow**: 
  1. Implements Symfony's UserInterface and PasswordAuthenticatedUserInterface
  2. Stores username, hashed password, and roles
  3. Manages one-to-many relationship with Favorites
  4. Handles password serialization for session security
- **Database Schema**:
  - `id` (Primary Key)
  - `username` (Unique, 180 chars)
  - `roles` (JSON array)
  - `password` (Hashed, 255 chars)
- **Security Features**: Password hashing, role-based access, session serialization

#### `src/Entity/Favorite.php`
- **Purpose**: Stores user's favorite characters
- **Workflow**: 
  1. Links users to their favorited Rick & Morty characters
  2. Stores character data locally (ID, name, image)
  3. Maintains many-to-one relationship with User
- **Database Schema**:
  - `id` (Primary Key)
  - `user_id` (Foreign Key to User)
  - `character_id` (Rick & Morty API character ID)
  - `character_name` (255 chars)
  - `character_image` (500 chars URL)

### Repository Classes

#### `src/Repository/UserRepository.php`
- **Purpose**: Custom database queries for User entity
- **Workflow**: Database operations, user lookups, custom queries
- **Details**: Extends Doctrine's ServiceEntityRepository

#### `src/Repository/FavoriteRepository.php`
- **Purpose**: Custom database queries for Favorite entity
- **Workflow**: Favorite lookups, user-specific queries
- **Details**: Handles complex favorite-related database operations

### Database Migration

#### `migrations/Version20250813005256.php`
- **Purpose**: Database schema creation and versioning
- **Workflow**: 
  1. **Up Migration**: Creates user, favorite, and messenger_messages tables
  2. **Down Migration**: Drops all tables for rollback
  3. **Schema Details**:
     - User table with unique username constraint
     - Favorite table with foreign key to user
     - Indexes for performance
- **Database Schema Evolution**: Handles schema changes and versioning

---

## 4. **Controller Layer (HTTP Request Handling)**

### `src/Controller/SecurityController.php`
- **Purpose**: Authentication (login/logout) management
- **Routes & Workflow**:
  
  **Route**: `GET /` (Login Page)
  - **Method**: `login()`
  - **Workflow**: 
    1. Check if user already logged in → redirect to characters
    2. Get authentication errors from failed attempts
    3. Preserve last entered username
    4. Render login form template
  - **Security**: Handles authentication state, error display
  
  **Route**: `POST /logout`
  - **Method**: `logout()`
  - **Workflow**: Symfony handles automatically (method never executes)
  - **Security**: Clears user session, redirects to login

### `src/Controller/RegistrationController.php`
- **Purpose**: New user account creation
- **Routes & Workflow**:
  
  **Route**: `GET|POST /registration`
  - **Method**: `register()`
  - **Workflow**: 
    1. **GET**: Display registration form
    2. **POST**: Process form submission
       - Validate username (3-50 chars, unique)
       - Validate password (6+ chars, confirmation match)
       - Hash password securely
       - Save user to database
       - Handle duplicate username errors
    3. **Success**: Redirect to login with success message
    4. **Failure**: Show form with error messages
  - **Security**: Password hashing, CSRF protection, input validation

### `src/Controller/CharacterController.php`
- **Purpose**: Rick & Morty character browsing and management
- **Routes & Workflow**:
  
  **Route**: `GET /characters` (Public)
  - **Method**: `list()`
  - **Workflow**: 
    1. Get page number from query string
    2. Fetch characters from Rick & Morty API
    3. Calculate pagination info (current page, total pages)
    4. Render character grid with navigation
  - **Features**: Pagination, error handling, public access
  
  **Route**: `GET /characters/{id}` (Protected)
  - **Method**: `show()`
  - **Workflow**: 
    1. **Security**: Requires login (ROLE_USER)
    2. Fetch single character from API
    3. Check if character in user's favorites
    4. Render detailed character page with favorite button
  - **Security**: Authentication required, CSRF tokens
  
  **Route**: `POST /characters/{id}/favorite` (Protected)
  - **Method**: `addToFavorites()`
  - **Workflow**: 
    1. Validate CSRF token
    2. Verify user authentication
    3. Check character exists in API
    4. Prevent duplicate favorites
    5. Create and save Favorite entity
    6. Show success/error flash messages
  - **Security**: CSRF protection, authentication checks
  
  **Route**: `POST /characters/{id}/unfavorite` (Protected)
  - **Method**: `removeFromFavorites()`
  - **Workflow**: 
    1. Validate CSRF token and user authentication
    2. Find user's favorite record
    3. Delete from database
    4. Show confirmation message
  - **Security**: CSRF protection, user ownership validation

### `src/Controller/FavoriteController.php`
- **Purpose**: User favorites management and display
- **Routes & Workflow**:
  
  **Route**: `GET /user/characters` (Protected)
  - **Method**: `list()`
  - **Workflow**: 
    1. **Security**: Requires login (ROLE_USER)
    2. Query user's favorites from database
    3. Render favorites list with remove options
    4. Handle empty state (no favorites)
  - **Features**: User-specific data, remove functionality
  
  **Route**: `POST /user/characters/{id}/remove` (Protected)
  - **Method**: `remove()`
  - **Workflow**: 
    1. Validate CSRF token and user authentication
    2. Find and remove favorite from database
    3. Redirect back to favorites list with message
  - **Security**: CSRF protection, user ownership validation

---

## 5. **Service Layer (Business Logic)**

### `src/Service/RickMortyApiService.php`
- **Purpose**: External API integration and caching
- **Dependencies**: HttpClient, Cache, Logger
- **Methods & Workflow**:
  
  **`getCharacters($page)`**
  - **Workflow**: 
    1. Check cache for existing data (5-minute TTL)
    2. If cache miss: Make HTTP request to API
    3. Parse response and extract characters + pagination info
    4. Cache results and return formatted data
    5. Handle API errors gracefully
  - **Features**: Caching, error handling, pagination support
  
  **`getCharacter($id)`**
  - **Workflow**: 
    1. Check cache for character data
    2. Make API request for single character
    3. Handle 404 (character not found) vs other errors
    4. Cache successful responses
  - **Features**: Individual character retrieval, error differentiation
  
  **`searchCharacters($name)`**
  - **Workflow**: 
    1. Cache search results by name hash
    2. Make API search request
    3. Handle no results vs API errors
    4. Return formatted search results
  - **Features**: Search functionality, intelligent caching
  
  **`getMultipleCharacters($ids)`**
  - **Workflow**: 
    1. Handle bulk character retrieval
    2. Make single API call for multiple IDs
    3. Normalize response format (single vs array)
    4. Cache bulk results
  - **Features**: Efficient batch operations
  
  **`clearCache()`**
  - **Purpose**: Development utility for cache management
  - **Workflow**: Clear all cached API data

---

## 6. **Form Layer**

### `src/Form/RegistrationFormType.php`
- **Purpose**: User registration form definition
- **Workflow**: 
  1. Defines form fields (username, password, confirm password)
  2. Sets validation rules and constraints
  3. Configures form rendering options
- **Features**: Built-in validation, CSRF protection, form rendering

---

## 7. **Template Layer (User Interface)**

### Base Templates

#### `templates/base.html.twig`
- **Purpose**: Master template for all pages
- **Workflow**: 
  1. Defines HTML structure and meta tags
  2. Includes Tailwind CSS for styling
  3. Sets up JavaScript imports
  4. Provides template blocks for content extension
- **Features**: Responsive design foundation, asset management

### Security Templates

#### `templates/security/login.html.twig`
- **Purpose**: User login interface
- **Workflow**: 
  1. Displays login form with username/password
  2. Shows authentication errors
  3. Provides registration link for new users
  4. Handles remember username functionality
- **Features**: Error display, form persistence, navigation

#### `templates/registration/register.html.twig`
- **Purpose**: User registration interface
- **Workflow**: 
  1. Renders registration form
  2. Displays validation errors
  3. Provides password confirmation field
  4. Links back to login page
- **Features**: Form validation display, user guidance

### Character Templates

#### `templates/character/list.html.twig`
- **Purpose**: Character browsing grid interface
- **Workflow**: 
  1. Displays character grid with images and basic info
  2. Provides pagination controls
  3. Links to individual character pages
  4. Handles loading states and errors
- **Features**: Responsive grid, pagination, error handling

#### `templates/character/show.html.twig`
- **Purpose**: Individual character detail page
- **Workflow**: 
  1. Shows full character information and large image
  2. Displays favorite/unfavorite button based on status
  3. Provides navigation back to character list
  4. Shows character attributes (species, origin, etc.)
- **Features**: Detailed character info, favorite management

### Favorite Templates

#### `templates/favorite/list.html.twig`
- **Purpose**: User's favorite characters display
- **Workflow**: 
  1. Shows grid of favorited characters
  2. Provides remove from favorites functionality
  3. Handles empty state (no favorites)
  4. Links to character detail pages
- **Features**: Favorite management, empty state handling

---

## 8. **Asset Management**

### Frontend Assets

#### `assets/app.js`
- **Purpose**: Main JavaScript application file
- **Workflow**: Application bootstrapping, component initialization
- **Features**: Symfony UX integration

#### `assets/styles/app.css` & `assets/styles/tailwind.css`
- **Purpose**: Styling and layout
- **Workflow**: Tailwind CSS compilation and custom styles
- **Features**: Responsive design, utility-first CSS

#### `public/css/tailwind.css`
- **Purpose**: Compiled CSS output
- **Workflow**: Generated from Tailwind configuration
- **Features**: Production-ready optimized CSS

#### `tailwind.config.js`
- **Purpose**: Tailwind CSS configuration
- **Workflow**: Defines design system, colors, spacing
- **Features**: Custom design tokens, responsive breakpoints

---

## 9. **Development & Testing**

### Package Management

#### `composer.json`
- **Purpose**: PHP dependency management
- **Key Dependencies**: 
  - Symfony 7.3 (framework)
  - Doctrine (ORM)
  - Twig (templating)
  - PHPUnit (testing)
- **Scripts**: Auto-scripts for cache clearing, asset installation

#### `package.json`
- **Purpose**: JavaScript/CSS dependency management
- **Workflow**: Node.js dependencies for frontend build process
- **Features**: Asset compilation, development tools

### Testing Configuration

#### `phpunit.dist.xml`
- **Purpose**: PHPUnit testing configuration
- **Workflow**: Defines test suites, bootstrap files, coverage settings
- **Features**: Unit and functional testing setup

#### `tests/bootstrap.php`
- **Purpose**: Test environment bootstrapping
- **Workflow**: Initializes test database and application state
- **Features**: Isolated test environment

---

## 10. **Environment & Configuration**

### Environment Files

#### `.env`, `.env.dev`, `.env.test`
- **Purpose**: Environment-specific configuration
- **Workflow**: 
  1. `.env`: Base configuration
  2. `.env.dev`: Development overrides
  3. `.env.test`: Testing environment
- **Contains**: Database URLs, API keys, debug settings

### Docker Configuration

#### `compose.yaml`, `compose.override.yaml`
- **Purpose**: Container orchestration
- **Workflow**: 
  1. Base services definition
  2. Development-specific overrides
  3. Database, PHP, web server setup
- **Features**: Consistent development environment

---

## 11. **Deployment & Build**

### Build Tools

#### `bin/console`
- **Purpose**: Symfony CLI tool
- **Workflow**: Database migrations, cache management, debugging
- **Commands**: `doctrine:migrations:migrate`, `cache:clear`, etc.

#### `symfony.lock`
- **Purpose**: Symfony Flex dependency resolution
- **Workflow**: Tracks installed packages and their recipes
- **Features**: Consistent package installation

---

## Application Request Flow

### 1. **Public Character List Request**
```
User → public/index.php → Kernel → Router → CharacterController::list() 
→ RickMortyApiService::getCharacters() → Cache/HTTP Client → API 
→ Template Rendering → Response
```

### 2. **User Login Process**
```
User → SecurityController::login() → Form Submission → Security System 
→ User Entity Authentication → Session Creation → Redirect to Characters
```

### 3. **Protected Character View**
```
Authenticated User → Security Check → CharacterController::show() 
→ API Service → Database Check (favorites) → Template with Auth State → Response
```

### 4. **Add to Favorites**
```
POST Request → CSRF Validation → Authentication Check → Character API Verification 
→ Duplicate Check → Favorite Entity Creation → Database Persist → Flash Message → Redirect
```

---

## Security Architecture

### Authentication Flow
1. **Form Login**: Username/password via SecurityController
2. **Session Management**: Symfony handles session persistence
3. **Password Security**: Auto-hashing with secure algorithms
4. **Route Protection**: Security.yaml access control

### Authorization Levels
- **Public**: Character list, login, registration
- **ROLE_USER**: Character details, favorites management
- **CSRF Protection**: All state-changing operations

### Data Protection
- **Password Hashing**: Automatic secure hashing
- **CSRF Tokens**: Form and AJAX protection
- **Input Validation**: Server-side validation for all inputs
- **SQL Injection Prevention**: Doctrine ORM parameterized queries

---

## Performance Features

### Caching Strategy
- **API Responses**: 5-minute TTL for Rick & Morty API calls
- **Character Data**: Individual and bulk caching
- **Search Results**: Cached by query hash
- **Development Cache**: Clearable for testing

### Database Optimization
- **SQLite**: Lightweight, file-based database
- **Indexes**: Optimized queries for user lookups
- **Relationships**: Efficient one-to-many mapping
- **Migrations**: Version-controlled schema changes

---

## Error Handling

### API Failures
- **Graceful Degradation**: Show cached data or error messages
- **Logging**: Comprehensive error logging for debugging
- **User Feedback**: Clear error messages for users
- **Retry Logic**: Available for production environments

### Form Validation
- **Server-side Validation**: All forms validated on backend
- **Flash Messages**: User-friendly error/success feedback
- **Form State Persistence**: Keep user input on validation failures
- **CSRF Protection**: Prevent malicious form submissions

---

This documentation provides a complete understanding of every file's role in the Rick and Morty Portal application, from HTTP request entry to database persistence and template rendering.
