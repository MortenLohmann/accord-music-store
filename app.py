from flask import Flask, render_template, jsonify, g, current_app
import mysql.connector
import logging
from config import get_config

# Create logging configuration
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('app.log'),
        logging.StreamHandler()
    ]
)

def create_app(config_class=None):
    """Application factory pattern"""
    app = Flask(__name__)
    
    # Load configuration
    if config_class is None:
        config_class = get_config()
    app.config.from_object(config_class)
    
    # Set up database connection
    @app.before_request
    def before_request():
        g.db = None
        g.cursor = None
    
    def get_db():
        if g.db is None:
            try:
                g.db = mysql.connector.connect(
                    host=app.config['DB_HOST'],
                    database=app.config['DB_NAME'],
                    user=app.config['DB_USER'],
                    password=app.config['DB_PASSWORD'],
                    port=app.config['DB_PORT']
                )
                g.cursor = g.db.cursor(dictionary=True)
            except Exception as e:
                current_app.logger.error(f"Database connection error: {e}")
                raise e
        return g.db, g.cursor
    
    @app.teardown_appcontext
    def close_db(e=None):
        db = g.pop('db', None)
        cursor = g.pop('cursor', None)
        
        if cursor:
            cursor.close()
        if db:
            db.close()
    
    # Make get_db available to routes
    app.get_db = get_db
    
    # Register routes
    @app.route('/')
    def home():
        # Get featured albums
        try:
            _, cursor = get_db()
            cursor.execute("""
                SELECT a.album_id, a.title, a.cover_art_url, ar.name as artist_name 
                FROM albums a
                JOIN artists ar ON a.artist_id = ar.artist_id
                LIMIT 6
            """)
            featured_albums = cursor.fetchall()
        except Exception as e:
            app.logger.error(f"Database error: {e}")
            # If query fails or tables don't exist yet, use sample data
            featured_albums = [
                {"album_id": 1, "title": "Album One", "cover_art_url": "/static/img/placeholder.jpg", "artist_name": "Artist 1"},
                {"album_id": 2, "title": "Album Two", "cover_art_url": "/static/img/placeholder.jpg", "artist_name": "Artist 2"},
                {"album_id": 3, "title": "Album Three", "cover_art_url": "/static/img/placeholder.jpg", "artist_name": "Artist 3"}
            ]
        
        return render_template('index.html', featured_albums=featured_albums)
    
    @app.route('/test-db')
    def test_db():
        try:
            db, cursor = get_db()
            cursor.execute("SELECT 1")
            result = cursor.fetchone()
            return jsonify({"status": "Database connection successful", "result": result})
        except Exception as e:
            return jsonify({"status": "Database connection failed", "error": str(e)}), 500
    
    # Add error handlers
    @app.errorhandler(404)
    def page_not_found(e):
        return render_template('404.html'), 404
    
    @app.errorhandler(500)
    def server_error(e):
        return render_template('500.html'), 500
    
    return app

# Only if running directly
if __name__ == '__main__':
    app = create_app()
    app.run(debug=app.config['DEBUG'])