# Accord Music Store - Application Structure

```
┌─────────────────────────────────────────────────────────────────────────┐
│                           ACCORD MUSIC STORE                             │
└────────────────┬────────────────────────────────┬──────────────────────┘
                 │                                 │
┌────────────────▼────────────────┐  ┌────────────▼─────────────┐
│          FRONTEND               │  │         BACKEND           │
│                                 │  │                           │
│  ┌─────────────────────────┐    │  │  ┌─────────────────────┐ │
│  │ Web Interface           │    │  │  │ PHP Components      │ │
│  │                         │    │  │  │                     │ │
│  │ ├── HTML                │    │  │  │ ├── index.php       │ │
│  │ │   ├── index.html      │    │  │  │ ├── products.php    │ │
│  │ │   ├── templates/      │    │  │  │ ├── product.php     │ │
│  │ │   │   ├── 404.html    │    │  │  │ ├── artist.php      │ │
│  │ │   │   ├── 500.html    │    │  │  │ ├── functions.php   │ │
│  │ │   │   └── index.html  │    │  │  │ └── db_connect.php  │ │
│  │ │                       │    │  │  │                     │ │
│  │ ├── CSS                 │    │  │  ├── Flask App         │ │
│  │ │   ├── styles.css      │    │  │  │ ├── app.py          │ │
│  │ │   └── static/css/*    │    │  │  │ ├── config.py       │ │
│  │ │                       │    │  │  │ └── requirements.txt│ │
│  │ └── JavaScript          │    │  │  │                     │ │
│  │     └── static/js/*     │    │  │  └── AI Integration    │ │
│  │                         │    │  │    ├── setup_openai.php│ │
│  └─────────────────────────┘    │  │    ├── ai_artist_bio.php  │ │
│                                 │  │    └── admin_artist_bios.php │ │
│  ┌─────────────────────────┐    │  │                     │ │
│  │ Assets                  │    │  └─────────────────────┘ │
│  │                         │    │                           │
│  │ ├── Images              │    │  ┌─────────────────────┐ │
│  │ │   ├── static/img/*    │    │  │ Configuration       │ │
│  │ │   ├── placeholder.svg │    │  │                     │ │
│  │ │   └── artist_placeholder.svg│ │ ├── config.php      │ │
│  │ │                       │    │  │ ├── .env            │ │
│  │ └── Dynamic Image Gen   │    │  │ └── .env.example    │ │
│  │     ├── create_placeholder_image.php │  │                     │ │
│  │     └── create_artist_placeholder.php│  └─────────────────────┘ │
│  │                         │    │                           │
│  └─────────────────────────┘    │  ┌─────────────────────┐ │
│                                 │  │ API                  │ │
└─────────────────────────────────┘  │                     │ │
                                     │ └── get_product.php │ │
                                     │                     │ │
                                     └─────────────────────┘ │
                                                             │
┌───────────────────────────────────▼─────────────────────────────────────┐
│                               DATABASE                                   │
│                                                                          │
│  ┌─────────────────────────┐   ┌──────────────────────┐                 │
│  │ MySQL Database          │   │ Tables               │                 │
│  │ (accord_db)             │   │                      │                 │
│  │                         │   │ ├── products         │                 │
│  │ ├── setup_database.sql  │   │ │   ├── id           │                 │
│  │ │                       │   │ │   ├── artist       │                 │
│  │ └── Schema Definition   │   │ │   ├── album_title  │                 │
│  │                         │   │ │   ├── format       │                 │
│  └─────────────────────────┘   │ │   ├── price        │                 │
│                                │ │   ├── image_url    │                 │
│                                │ │   ├── release_date │                 │
│                                │ │   ├── genre        │                 │
│                                │ │   ├── media_count  │                 │
│                                │ │   ├── description  │                 │
│                                │ │   ├── artist_bio   │                 │
│                                │ │   ├── status       │                 │
│                                │ │   └── created_at   │                 │
│                                │ │                    │                 │
│                                │ ├── users (future)   │                 │
│                                │ │   ├── id           │                 │
│                                │ │   ├── username     │                 │
│                                │ │   ├── password     │                 │
│                                │ │   ├── email        │                 │
│                                │ │   └── created_at   │                 │
│                                │ │                    │                 │
│                                │ ├── orders (future)  │                 │
│                                │ │   ├── id           │                 │
│                                │ │   ├── user_id      │                 │
│                                │ │   ├── total_amount │                 │
│                                │ │   ├── status       │                 │
│                                │ │   └── created_at   │                 │
│                                │ │                    │                 │
│                                │ └── order_items (future) │            │
│                                │     ├── id           │                 │
│                                │     ├── order_id     │                 │
│                                │     ├── product_id   │                 │
│                                │     ├── quantity     │                 │
│                                │     └── price        │                 │
│                                │                      │                 │
│                                └──────────────────────┘                 │
│                                                                          │
└──────────────────────────────────────────────────────────────────────────┘
```

## Data Flow

```
┌──────────────┐      ┌───────────────┐      ┌───────────────┐
│   Browser    │◄────►│  Frontend     │◄────►│ PHP/Flask     │
│   Client     │      │  (HTML/CSS/JS)│      │ Backend       │
└──────────────┘      └───────────────┘      └───────┬───────┘
                                                    │
                                                    ▼
                                            ┌───────────────┐
                                            │  Database     │
                                            │  (MySQL)      │
                                            └───────────────┘
                                                    │
                                                    ▼
                                            ┌───────────────┐
                                            │ OpenAI API    │
                                            │ (External)    │
                                            └───────────────┘
```

## Key Components

### Frontend
- **HTML/Templates**: Provides the structure for web pages
- **CSS**: Styling for the web interface
- **JavaScript**: Client-side interactivity
- **Assets**: Images and dynamically generated placeholder images

### Backend
- **PHP Components**: Main business logic for the music store
- **Flask Application**: Additional web application functionality
- **AI Integration**: OpenAI API for generating artist biographies
- **Configuration**: Environment variables and settings
- **API**: Endpoints for retrieving product data

### Database
- **MySQL Database**: Persistent storage for application data
- **Tables**: 
  - Products: Music albums and their details
  - Users: Customer accounts (planned for future)
  - Orders: Purchase records (planned for future)
  - Order Items: Items within orders (planned for future)

## Component Interactions

1. Users access the store through web browsers
2. Frontend components render the UI
3. Backend processes requests and interacts with the database
4. Database stores and retrieves music product information
5. OpenAI API integration enriches product data with AI-generated artist biographies
6. Admin interfaces allow for content management 