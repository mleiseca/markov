curl "http://localhost:8080/generate?model=r" > model-republican.json
curl "http://localhost:8080/generate?model=d" > model-democratic.json
curl "http://localhost:8080/generate?model=b" > model-combined.json
