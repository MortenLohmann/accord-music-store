import os
from dotenv import load_dotenv

# Load environment variables from .env file
load_dotenv()

class Config:
    """Base configuration class."""
    SECRET_KEY = os.environ.get('SECRET_KEY', 'dev-key-for-development-only')
    DEBUG = False
    TESTING = False
    
    # Database Configuration
    DB_HOST = os.environ.get('DB_HOST', 'localhost')
    DB_NAME = os.environ.get('DB_NAME', 'accord_db')
    DB_USER = os.environ.get('DB_USER', 'website_user')
    DB_PASSWORD = os.environ.get('DB_PASSWORD', 'another_secure_password')
    DB_PORT = int(os.environ.get('DB_PORT', 3306))
    
    # OpenAI API Configuration
    OPENAI_API_KEY = os.environ.get('OPENAI_API_KEY', 'YOUR_API_KEY')
    OPENAI_API_MODEL = os.environ.get('OPENAI_API_MODEL', 'gpt-3.5-turbo-instruct')


class DevelopmentConfig(Config):
    """Development configuration."""
    DEBUG = True


class ProductionConfig(Config):
    """Production configuration."""
    # In production, do not use default values
    # Ensure all environment variables are properly set
    pass


class TestingConfig(Config):
    """Testing configuration."""
    TESTING = True
    DB_NAME = 'accord_db_test'  # Use a separate test database


# Configuration dictionary
config = {
    'development': DevelopmentConfig,
    'production': ProductionConfig,
    'testing': TestingConfig,
    'default': DevelopmentConfig
}

# Set the active configuration
def get_config():
    env = os.environ.get('FLASK_ENV', 'development')
    return config.get(env, config['default']) 