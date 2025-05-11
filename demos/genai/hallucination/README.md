# LLM Hallucination Demo

This demo showcases how LLMs can hallucinate information that doesn't exist in reality. In this example, the LLM simulates a product database that doesn't actually exist.

## Overview

This demonstration simulates a product search application:

1. The LLM is prompted to act as if it were a database-backed web application
2. The application has a single product in its "inventory"
3. The LLM hallucinates search results based on user queries
4. No actual database exists behind the application

## Running the Demo

### Using Docker

1. Build and run the container:
   ```
   docker-compose up -d
   ```

2. The first run will take some time as it downloads the Ollama LLM model
3. Access the demo at: http://localhost:8081

## Demonstration Details

### Basic Product Search

Try searching for terms like "lock" or "security" to see the LLM hallucinate legitimate product search results based on the single product it was told exists.

Try searching for completely unrelated terms like "potato" to see how the system responds with no results.

This demo also shows how an LLM can hallucinate completely fictional data when given input that resembles database queries. Try these patterns:

1. **Simple Query Pattern**: `' OR 1=1;--`
2. **Complex Query Pattern**: `' UNION SELECT null, username, password, null FROM users;--`

The LLM will respond as if these were actual database queries, generating completely fabricated data that wasn't specified in its instructions.

## Understanding the Phenomenon

This demo illustrates several important concepts:

1. **LLM Hallucination**: The model generates plausible but fabricated responses based on prompting
2. **Prompt Influence**: Different prompt patterns can cause the LLM to generate different types of hallucinated content
3. **Contextual Response**: The LLM's responses are shaped by both its initial instructions and user input

## Implications for AI Systems

When using LLMs in production applications, be aware that:

1. LLMs may confidently present fictional information as if it were real
2. User input can significantly influence the type and nature of hallucinated content
3. System prompts should be designed to minimize unwanted hallucination
4. Output should be validated against known ground truth when accuracy is critical

## Educational Purpose Only

This application is for educational purposes only.
