document.addEventListener("DOMContentLoaded", function () {
    const username = "admin";
    const password = "sigma1234";

    const loginForm = document.getElementById('login-form');
    const loginError = document.getElementById('login-error');
    const dashboardContainer = document.getElementById('dashboard-container');
    const loginContainer = document.getElementById('login-container');

    const incomeForm = document.getElementById('income-form');
    const expenseForm = document.getElementById('expense-form');
    const totalIncomeEl = document.getElementById('totalIncome');
    const totalExpensesEl = document.getElementById('totalExpenses');

    let incomes = [];
    let expenses = [];

    function updateTotals() {
        const totalIncome = incomes.reduce((sum, inc) => sum + inc.amount, 0);
        const totalExpenses = expenses.reduce((sum, exp) => sum + exp.amount, 0);

        totalIncomeEl.textContent = totalIncome.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        totalExpensesEl.textContent = totalExpenses.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function formatData() {
        let dataText = "Pemasukan:\n";
        incomes.forEach(income => {
            dataText += `- ${income.description}: ${income.amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}\n`;
        });

        dataText += "\nPengeluaran:\n";
        expenses.forEach(expense => {
            dataText += `- ${expense.description}: ${expense.amount.toLocaleString('en-US', { minimumFractionDigits: 2 })}\n`;
        });

        // Send data to the server
        fetch('writeData.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `data=${encodeURIComponent(dataText)}`
        })
        .then(response => response.text())
        .then(result => {
            console.log('Data written to file:', result);
        })
        .catch(error => {
            console.error('Error writing data:', error);
        });
    }

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const user = document.getElementById('username').value;
        const pass = document.getElementById('password').value;

        if (user === username && pass === password) {
            loginContainer.style.display = 'none';
            dashboardContainer.style.display = 'block';
        } else {
            loginError.style.display = 'block';
        }
    });

    incomeForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const amount = parseFloat(document.getElementById('income-amount').value);
        const description = document.getElementById('income-description').value;
        incomes.push({ amount, description });
        updateTotals();
        formatData();
        incomeForm.reset();
    });

    expenseForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const amount = parseFloat(document.getElementById('expense-amount').value);
        const description = document.getElementById('expense-description').value;
        expenses.push({ amount, description });
        updateTotals();
        formatData();
        expenseForm.reset();
    });

    updateTotals();
});
