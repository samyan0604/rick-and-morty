# Rick and Morty Portal 🛸

A Symfony web application for browsing Rick and Morty characters with user authentication and favorites functionality.

## 📋 Project Brief

Build a small site with:
- Login and registration system
- Public characters list from the Rick and Morty API
- Protected character profile pages
- Protected user favorites page

## 🛠 Tech Stack

- **Framework**: Symfony 7.3
- **Language**: PHP 8.2+
- **Database**: SQLite
- **ORM**: Doctrine
- **Templates**: Twig
- **HTTP Client**: Symfony HttpClient
- **Authentication**: Symfony Security (sessions)

## 📁 Project Structure

```
rick-and-morty/
├── src/
│   ├── Entity/
│   │   ├── User.php          ✅ Complete
│   │   └── Favorite.php      ✅ Complete
│   ├── Repository/
│   │   ├── UserRepository.php
│   │   └── FavoriteRepository.php
│   └── Service/
│       └── RickMortyApiService.php  ✅ Skeleton created
├── config/
│   ├── packages/
│   │   ├── doctrine.yaml     ✅ Configured for SQLite
│   │   └── security.yaml     ✅ Basic user provider setup
│   └── routes.yaml
├── templates/
├── var/
│   └── data.db              ✅ Database created
└── migrations/              ✅ Initial migration run
```

## ✅ What's Already Done

### Project Foundation
- ✅ Symfony 7.3 project created with all dependencies
- ✅ SQLite database configured and migrated
- ✅ **User Entity**: Complete with username, password, roles, and favorites relationship
- ✅ **Favorite Entity**: Complete with user relationship, characterId, characterName, characterImage
- ✅ **RickMortyApiService**: Skeleton created with HttpClient and Cache dependencies
- ✅ Security bundle and Doctrine configured

## 🎯 Development Checklist

Follow these steps in order to build the application systematically:

### 1. Orientation & Planning
- ✅ Extract routes/pages from requirements (`/`, `/registration`, `/characters/`, `/characters/{id}`, `/user/characters/`)
- ✅ Note deliverables (login, registration, public list, protected profile, favorites)
- ✅ Project skeleton created, SQLite configured, entities built
- ✅ README outline completed

### 2. Learn Core Symfony Concepts
- ✅ Study Symfony routing and controllers
- ✅ Understand Twig templating system
- ✅ Learn Symfony Security component
- ✅ Map Django knowledge to Symfony equivalents
- ✅ Review Doctrine ORM relationships

### 3. Repository Setup & Git
- ✅ Add comprehensive `.gitignore` file
- ✅ Initialize git repository
- ✅ Create initial commit with project scaffold
- ✅ Clean up project structure

### 4. Build Authentication System
- ✅ Configure `config/packages/security.yaml` with firewall and access control
- ✅ Create `SecurityController` for login/logout at `/`
- [ ] Create `RegistrationController` for user signup at `/registration`
- [ ] Create `RegistrationFormType` form class
- [ ] Implement password hashing in registration
- [ ] Add CSRF protection to forms
- [ ] Create login template (`templates/security/login.html.twig`)
- [ ] Create registration template (`templates/registration/register.html.twig`)
- [ ] Test authentication flow manually

### 5. Integrate Rick & Morty API
- [ ] Complete `RickMortyApiService` class:
  - [ ] Add `getCharacters($page = 1)` method
  - [ ] Add `getCharacter($id)` method
  - [ ] Implement caching for API responses (5-10 minutes)
  - [ ] Add error handling for API failures
  - [ ] Handle rate limiting gracefully

### 6. Implement Public Characters List (`/characters/`)
- [ ] Create `CharacterController` with list action
- [ ] Call Rick & Morty API service
- [ ] Create `templates/character/list.html.twig`:
  - [ ] Grid view layout
  - [ ] Display character image, name, and status
  - [ ] Add links to individual character pages
  - [ ] Handle empty states
- [ ] Add optional pagination for large result sets
- [ ] Test public access (no authentication required)

### 7. Implement Protected Character Profile (`/characters/{id}`)
- [ ] Add character show action to `CharacterController`
- [ ] Configure route protection in security.yaml
- [ ] Create `templates/character/show.html.twig`:
  - [ ] Display full character details (name, status, species, origin, location)
  - [ ] Show character image
  - [ ] Add "Save to Favorites" button/form (POST request)
- [ ] Implement add-to-favorites functionality
- [ ] Handle API errors (character not found)
- [ ] Test access control (redirect to login if not authenticated)

### 8. Implement Protected User Favorites (`/user/characters/`)
- [ ] Create `FavoriteController` with list action
- [ ] Query user's favorites using Doctrine relationship
- [ ] Create `templates/favorite/list.html.twig`:
  - [ ] Display favorited characters
  - [ ] Add "Remove from Favorites" option
  - [ ] Handle empty state (no favorites yet)
- [ ] Prevent duplicate favorites in database
- [ ] Add remove-from-favorites functionality
- [ ] Test route protection and favorites persistence

### 9. Navigation & User Experience
- [ ] Create base template (`templates/base.html.twig`) with:
  - [ ] Responsive navbar
  - [ ] Context-based navigation links (Characters, Profile, Favorites when logged in)
  - [ ] Logout link when authenticated
  - [ ] Login/Register links when not authenticated
- [ ] Add flash messages for user feedback
- [ ] Ensure accessibility-friendly markup
- [ ] Add basic styling (optional: Bootstrap or custom CSS)

### 10. Performance Optimization
- [ ] Verify API response caching is working
- [ ] Implement lazy-loading for character images
- [ ] Minimize API calls by caching results
- [ ] Optimize database queries
- [ ] Add loading states for API calls

### 11. Testing & Quality Assurance
- [ ] Create service-level test for RickMortyApiService
- [ ] Manual functional testing:
  - [ ] Registration flow
  - [ ] Login/logout flow
  - [ ] Public character list access
  - [ ] Protected routes redirect correctly
  - [ ] Add/remove favorites functionality
  - [ ] Navigation works correctly
- [ ] Test error scenarios (API down, invalid character ID, etc.)
- [ ] Verify CSRF protection is working

### 12. Documentation & Deployment Prep
- [ ] Finalize README with:
  - [ ] Prerequisites (PHP 8.2+, Composer, Symfony CLI)
  - [ ] Setup instructions
  - [ ] Known limitations
  - [ ] Architecture decisions
- [ ] Document any assumptions or trade-offs made
- [ ] Prepare environment configuration for production

### 13. Deployment (Optional)
- [ ] Set up hosting on Render/Heroku/similar
- [ ] Configure environment variables
- [ ] Run migrations in production
- [ ] Smoke test live site
- [ ] Ensure repository is public
- [ ] Document live URL

### 14. Optional Stretch Goals
- [ ] Advanced pagination with page numbers
- [ ] Enhanced flash messages for favorites actions
- [ ] API retry logic with exponential backoff
- [ ] User profile page with account information
- [ ] Character search functionality
- [ ] Favorite characters sorting/filtering

## 🚀 Getting Started

1. **Start the development server:**
   ```bash
   cd /Users/samyan/Documents/rick-and-morty
   symfony server:start
   ```

2. **Next Step**: Begin with Step 2 - Learn Core Symfony Concepts, then move to Step 3 - Repository Setup

## 📚 Key Resources

- **Rick and Morty API**: https://rickandmortyapi.com/api/character
- **Symfony Documentation**: https://symfony.com/doc/current/
- **Symfony Security**: https://symfony.com/doc/current/security.html
- **Symfony Forms**: https://symfony.com/doc/current/forms.html
- **Twig Templates**: https://twig.symfony.com/doc/3.x/

## 💡 Development Tips

- Work through checklist items sequentially
- Test each feature before moving to the next
- Keep controllers thin - use services for business logic
- Commit frequently with descriptive messages
- Use Symfony's built-in security features
- Cache API responses to avoid rate limiting

---

**Current Status**: Foundation, Symfony concepts, and Git setup complete ✅  
**Next Step**: Build Authentication System (Step 4)
