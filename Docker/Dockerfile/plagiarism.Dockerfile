# Use the official Python image as the base image
FROM python:3.11-slim

# Set the working directory in the container
WORKDIR /app

# Copy the requirements file into the container
COPY plagiarism-checking/requirements.txt .

# Install the dependencies
RUN pip install --no-cache-dir -r requirements.txt

# Copy the application code into the container
COPY plagiarism-checking/ .

# Expose the port the app runs on
EXPOSE 9001

# Set the environment variable for Django
ENV PYTHONUNBUFFERED=1

# Run database migrations and start the Django development server
CMD ["sh", "-c", "python stopWordRemove.py && python manage.py migrate && python manage.py runserver 0.0.0.0:9001"]