# Accord Music Store - Layered Architecture

```
┌───────────────────────────────────── LAYERED ARCHITECTURE ─────────────────────────────────────┐
│                                                                                                 │
│ ┌─────────────────────────────────── PRESENTATION LAYER ───────────────────────────────────┐   │
│ │                                                                                           │   │
│ │  ┌─────────────┐      ┌─────────────┐      ┌─────────────┐      ┌─────────────┐         │   │
│ │  │  HTML/CSS   │      │ Templates   │      │ Static Assets│      │ Javascript  │         │   │
│ │  │             │      │             │      │             │      │             │         │   │
│ │  │ - index.html│      │ - 404.html  │      │ - images    │      │ - UI scripts │         │   │
│ │  │ - styles.css│      │ - 500.html  │      │ - SVG files │      │ - static/js  │         │   │
│ │  └─────────────┘      └─────────────┘      └─────────────┘      └─────────────┘         │   │
│ │                                                                                           │   │
│ └───────────────────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                                 │
│ ┌─────────────────────────────────── APPLICATION LAYER ───────────────────────────────────┐   │
│ │                                                                                           │   │
│ │  ┌─────────────────────┐      ┌─────────────────────┐      ┌─────────────────────┐      │   │
│ │  │  Product Management │      │  Artist Management  │      │  Admin Interface    │      │   │
│ │  │                     │      │                     │      │                     │      │   │
│ │  │ - index.php         │      │ - artist.php       │      │ - admin_artist_bios.php │  │   │
│ │  │ - products.php      │      │ - ai_artist_bio.php│      │ - setup_openai.php  │      │   │
│ │  │ - product.php       │      │                     │      │                     │      │   │
│ │  │ - get_product.php   │      │                     │      │                     │      │   │
│ │  └─────────────────────┘      └─────────────────────┘      └─────────────────────┘      │   │
│ │                                                                                           │   │
│ │  ┌─────────────────────┐      ┌─────────────────────┐                                    │   │
│ │  │  Flask Application  │      │  Utility Functions  │                                    │   │
│ │  │                     │      │                     │                                    │   │
│ │  │ - app.py            │      │ - functions.php    │                                    │   │
│ │  │ - route handlers    │      │ - image generation │                                    │   │
│ │  └─────────────────────┘      └─────────────────────┘                                    │   │
│ │                                                                                           │   │
│ └───────────────────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                                 │
│ ┌─────────────────────────────────── DATA ACCESS LAYER ───────────────────────────────────┐   │
│ │                                                                                           │   │
│ │  ┌─────────────────────┐      ┌─────────────────────┐      ┌─────────────────────┐      │   │
│ │  │  Database Access    │      │  Configuration      │      │  File Storage       │      │   │
│ │  │                     │      │                     │      │                     │      │   │
│ │  │ - db_connect.php    │      │ - config.php       │      │ - image handling    │      │   │
│ │  │ - SQL queries       │      │ - .env             │      │ - file operations   │      │   │
│ │  │                     │      │ - config.py        │      │                     │      │   │
│ │  └─────────────────────┘      └─────────────────────┘      └─────────────────────┘      │   │
│ │                                                                                           │   │
│ └───────────────────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                                 │
│ ┌─────────────────────────────────── INFRASTRUCTURE LAYER ────────────────────────────────┐   │
│ │                                                                                           │   │
│ │  ┌─────────────────────┐      ┌─────────────────────┐      ┌─────────────────────┐      │   │
│ │  │  Database Storage   │      │  External Services  │      │  System Resources   │      │   │
│ │  │                     │      │                     │      │                     │      │   │
│ │  │ - MySQL Database    │      │ - OpenAI API        │      │ - File System      │      │   │
│ │  │ - accord_db         │      │ - (Future) Payment  │      │ - Environment      │      │   │
│ │  │ - DB tables/schema  │      │   Services          │      │ - Web Server       │      │   │
│ │  └─────────────────────┘      └─────────────────────┘      └─────────────────────┘      │   │
│ │                                                                                           │   │
│ └───────────────────────────────────────────────────────────────────────────────────────────┘   │
│                                                                                                 │
└─────────────────────────────────────────────────────────────────────────────────────────────────┘
```

## Layer Descriptions

### 1. Presentation Layer
This layer is responsible for user interface components and direct user interaction. It includes:
- **HTML/CSS**: Core structure and styling for the web interface
- **Templates**: Reusable UI components and error pages
- **Static Assets**: Images, SVGs, and other media resources
- **JavaScript**: Client-side interactivity and UI enhancements

### 2. Application Layer
This layer contains the business logic and core functionality of the application:
- **Product Management**: Handles displaying, filtering, and serving product information
- **Artist Management**: Manages artist information and biographies
- **Admin Interface**: Provides administrative controls for content management
- **Flask Application**: Additional web application functionality
- **Utility Functions**: Shared functionality used across the application

### 3. Data Access Layer
This layer manages data retrieval, storage, and configuration:
- **Database Access**: Handles database connections and queries
- **Configuration**: Manages environment variables and application settings
- **File Storage**: Handles file operations and image processing

### 4. Infrastructure Layer
This layer provides the foundational services and resources:
- **Database Storage**: The MySQL database and its schema
- **External Services**: Integration with third-party APIs like OpenAI
- **System Resources**: File system, environment, and server components

## Cross-Cutting Concerns

These aspects affect multiple layers of the application:

- **Security**: Authentication, authorization, and data protection
- **Logging**: Error tracking and application monitoring
- **Performance**: Optimization for speed and resource usage

## Data Flow Between Layers

1. User actions in the **Presentation Layer** trigger requests
2. Requests are processed by the **Application Layer**, which contains business logic
3. The **Application Layer** uses the **Data Access Layer** to retrieve or store data
4. The **Data Access Layer** interacts with the **Infrastructure Layer** to execute operations
5. Results flow back up through the layers to present information to the user

## Benefits of This Architecture

- **Separation of Concerns**: Each layer has a distinct responsibility
- **Maintainability**: Changes in one layer have minimal impact on others
- **Testability**: Layers can be tested independently
- **Scalability**: Individual layers can be scaled as needed
- **Flexibility**: Components can be replaced without affecting the entire system 