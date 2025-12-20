# Question Bank Management System - Setup Instructions

## ⚠️ Important Setup Steps

Before running the application, you need to complete these manual steps:

### Step 1: Create the Database
1. Open **phpMyAdmin** by navigating to `http://localhost/phpmyadmin`
2. Click on "**New**" in the left sidebar
3. Create a new database named: **`editor-laravel`**
4. Character Set: **utf8mb4_unicode_ci** (recommended)
5. Click "**Create**"

### Step 2: Update .env File (Already Done)
The `.env.example` has been updated. Copy it to `.env`:
```bash
copy .env.example .env
```

Database configuration:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=editor-laravel
DB_USERNAME=root
DB_PASSWORD=
```

### Step 3: Install Dependencies
```bash
# Install PHP dependencies
composer install

# Install Node dependencies (Tailwind CSS)
npm install

# Build assets
npm run dev
```

### Step 4: Run Migrations and Seeders
```bash
# Run migrations and seed database
php artisan migrate:fresh --seed
```

This will:
- Create 3 tables: `categories`, `questions`, `options`
- Seed 5 categories: General Knowledge, Science, Mathematics, History, Programming
- Seed 10 sample questions with rich text content (tables, lists, formatting)

### Step 5: Start Development Server
```bash
# In terminal 1: Build assets
npm run dev

# In terminal 2: Start Laravel server
php artisan serve
```

### Step 6: Access the Application
Open your browser and navigate to:
```
http://localhost:8000
```

You'll be automatically redirected to the Questions Index page.

---

## 🎯 Features Implemented

✅ **Complete CRUD Operations**
- Create, Read, Update, Delete questions
- Category management with relationships
- Multiple-choice options with correct answer marking

✅ **TinyMCE Integration**
- Rich text editor for question content
- Table support
- Lists (bulleted and numbered)
- Text formatting (bold, italic, underline)
- Professional toolbar

✅ **Dynamic Options**
- Add/remove options dynamically with JavaScript
- Radio button to mark correct answer
- Minimum 2 options validation

✅ **Professional UI**
- Modern Tailwind CSS design
- Color-coded badges for difficulty (Easy/Medium/Hard)
- Category badges
- Status indicators (Draft/Published)
- Search functionality
- Category filtering
- Responsive design

✅ **Database Structure**
- Categories table with slug
- Questions table with difficulty, marks, status
- Options table with is_correct flag
- Proper foreign key relationships with cascade delete

---

## 📝 For Client Demo

When demoing to the client, showcase these features:

1. **Create a Question** with:
   - A **3x3 table** comparing options
   - A **bulleted list** with question hints
   - **Bold and italic** formatting

2. **Edit Questions** - Show how existing questions can be updated

3. **Filter & Search** - Demonstrate category filtering and text search

4. **Rich Content** - Show the seeded questions with tables and lists

5. **Professional Design** - Highlight the modern UI with color-coded badges

---

## 🐛 Troubleshooting

**Migration fails?**
- Ensure XAMPP MySQL is running
- Ensure database `editor-laravel` exists
- Check .env credentials

**Assets not loading?**
- Run `npm install`
- Start `npm run dev` in a separate terminal

**500 Error?**
- Generate app key: `php artisan key:generate`
- Clear config: `php artisan config:clear`

---

## 📂 Project Structure

```
editor-laravel/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── QuestionController.php
│   │   └── Requests/
│   │       ├── StoreQuestionRequest.php
│   │       └── UpdateQuestionRequest.php
│   └── Models/
│       ├── Category.php
│       ├── Question.php
│       └── Option.php
├── database/
│   ├── migrations/
│   │   ├── 2024_01_01_000001_create_categories_table.php
│   │   ├── 2024_01_01_000002_create_questions_table.php
│   │   └── 2024_01_01_000003_create_options_table.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   └── questions/
│   │       ├── index.blade.php
│   │       ├── create.blade.php
│   │       └── edit.blade.php
│   └── css/
│       └── app.css
├── routes/
│   └── web.php
└── tailwind.config.js
```

---

## 🎉 System is Ready!

All files have been created. Follow the setup steps above to get the system running! 🚀
