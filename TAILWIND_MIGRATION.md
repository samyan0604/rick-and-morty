# Tailwind CSS Migration Guide

## Current CSS → Tailwind Conversion

### 1. Login Page Styles

#### Current CSS:
```css
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #66eaa1 0%, #1778df 100%);
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
```

#### Tailwind Classes:
```html
<body class="font-sans bg-gradient-to-br from-green-400 to-blue-600 flex justify-center items-center min-h-screen">
```

#### Current CSS:
```css
.login-container {
    background-color: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    width: 100%;
    max-width: 400px;
}
```

#### Tailwind Classes:
```html
<div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md">
```

### 2. Form Elements

#### Current CSS:
```css
input[type="text"], input[type="password"] {
    width: 100%;
    padding: 15px 15px 15px 45px;
    border: 2px solid #e1e5e9;
    border-radius: 8px;
    font-size: 16px;
    transition: all 0.3s ease;
    background-color: #f8f9fa;
}
```

#### Tailwind Classes:
```html
<input class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-lg text-base transition-all duration-300 bg-gray-50 focus:outline-none focus:border-blue-500 focus:bg-white focus:shadow-lg">
```

### 3. Buttons

#### Current CSS:
```css
.btn {
    width: 100%;
    padding: 15px;
    background: linear-gradient(135deg, #66eaa1, #1778df);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}
```

#### Tailwind Classes:
```html
<button class="w-full py-4 px-4 bg-gradient-to-r from-green-400 to-blue-600 text-white rounded-lg text-base font-semibold cursor-pointer transition-all duration-300 hover:transform hover:-translate-y-0.5 hover:shadow-lg">
```

### 4. Custom Components (Already Created)

Use these custom classes from your `tailwind.css`:
- `.btn-primary` - Primary button with Rick green
- `.btn-secondary` - Secondary button with portal blue
- `.card` - Card with hover effects
- `.portal-gradient` - Rick and Morty gradient
- `.animate-portal-spin` - Portal spinning animation

## Migration Steps:

1. **Start with new components** - Use Tailwind for any new templates
2. **Replace one template at a time** - Convert existing templates gradually
3. **Use custom components** - Leverage the pre-built components in your Tailwind config
4. **Remove old CSS files** - Once converted, remove the old CSS files

## Example Template Conversion:

### Before (with custom CSS):
```twig
<div class="login-container">
    <h1>Login</h1>
    <form>
        <input type="text" class="form-control">
        <button class="btn">Login</button>
    </form>
</div>
```

### After (with Tailwind):
```twig
<div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-md">
    <h1 class="text-3xl font-bold text-gray-800 text-center mb-6">Login</h1>
    <form>
        <input type="text" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:border-portal-blue focus:outline-none transition-colors">
        <button class="btn-primary w-full mt-4">Login</button>
    </form>
</div>
```

## Rick and Morty Theme Colors Available:
- `rick-green`: #97ce4c
- `morty-yellow`: #f2e53d  
- `portal-blue`: #00b5d8
- `space-dark`: #0f172a

## Next Steps:
1. Start with your login template
2. Convert character list template
3. Update character detail template
4. Convert favorites template
5. Remove old CSS files
