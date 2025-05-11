FROM python:3.10-slim

# Install Ollama (this layer will be cached unless the base image changes)
RUN apt-get update && apt-get install -y \
    curl \
    && curl -fsSL https://ollama.com/install.sh | sh

# Set working directory
WORKDIR /app

# Install Python dependencies (this layer will be cached unless requirements.txt changes)
COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application code (this will rebuild if code changes, but previous layers remain cached)
COPY www/ .

# Expose the port
EXPOSE 8000

# Create a simple script to start both Ollama and the Flask app
RUN echo '#!/bin/bash\n\
# Start Ollama in the background\n\
ollama serve &\n\
# Wait for Ollama to start up\n\
sleep 5\n\
# Start the Flask app\n\
python app.py\n' > /start.sh && \
chmod +x /start.sh

CMD ["/start.sh"] 