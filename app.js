const express = require('express');
const app = express();
const PORT = 3000;

app.use(express.json());

// Endpoint to add a new floor
app.post('/floor_add_new', (req, res) => {
    const { floorData } = req.body;
    // Logic to add a new floor
    // Respond with success message and added floor data
    res.status(201).json({ message: 'Floor added successfully', floor: floorData });
});

// Endpoint to add a new plan
app.post('/plan_add_new', (req, res) => {
    const { planData } = req.body;
    // Logic to add a new plan
    // Respond with success message and added plan data
    res.status(201).json({ message: 'Plan added successfully', plan: planData });
});

app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});