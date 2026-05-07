import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const calculator = document.getElementById('floating-calculator');
    const toggle = document.getElementById('calculator-toggle');
    const closeBtn = document.getElementById('calculator-close');
    const display = calculator?.querySelector('.calculator-display');
    const buttons = calculator?.querySelectorAll('.calculator-button') || [];

    let currentInput = '0';
    let previousInput = '';
    let operator = '';
    let shouldResetDisplay = false;

    const render = (value) => {
        if (!display) return;
        if (value === '' || value === 'Error') {
            display.value = value || '0';
            return;
        }
        // Format number with thousand separators using dots
        const num = parseFloat(value.replace(/,/g, ''));
        if (!isNaN(num)) {
            const parts = num.toString().split('.');
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            display.value = parts.join('.');
        } else {
            display.value = value;
        }
    };

    const resetCalculator = () => {
        currentInput = '0';
        previousInput = '';
        operator = '';
        shouldResetDisplay = false;
        render(currentInput);
    };

    const appendNumber = (number) => {
        if (shouldResetDisplay) {
            currentInput = number;
            shouldResetDisplay = false;
        } else {
            if (currentInput === '0' && number !== '.') {
                currentInput = number;
            } else {
                currentInput += number;
            }
        }
        render(currentInput);
    };

    const chooseOperator = (nextOperator) => {
        if (previousInput !== '' && !shouldResetDisplay) {
            calculate();
        }
        operator = nextOperator;
        previousInput = currentInput;
        shouldResetDisplay = true;
    };

    const calculate = () => {
        let result;
        const prev = parseFloat(previousInput);
        const current = parseFloat(currentInput);

        if (isNaN(prev) || isNaN(current)) return;

        switch (operator) {
            case '+':
                result = prev + current;
                break;
            case '-':
                result = prev - current;
                break;
            case '×':
                result = prev * current;
                break;
            case '÷':
                result = prev / current;
                break;
            default:
                return;
        }

        currentInput = result.toString();
        operator = '';
        previousInput = '';
        render(currentInput);
    };

    if (toggle) {
        toggle.addEventListener('click', () => {
            calculator?.classList.toggle('open');
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            calculator?.classList.remove('open');
        });
    }

    buttons.forEach((button) => {
        button.addEventListener('click', () => {
            const action = button.dataset.action;
            const value = button.dataset.value;

            if (action === 'clear') {
                resetCalculator();
                return;
            }

            if (action === 'delete') {
                if (currentInput.length > 1) {
                    currentInput = currentInput.slice(0, -1);
                } else {
                    currentInput = '0';
                }
                render(currentInput);
                return;
            }

            if (action === 'equals') {
                if (previousInput !== '' && operator !== '') {
                    calculate();
                }
                shouldResetDisplay = true;
                return;
            }

            if (value) {
                if (['+', '-', '×', '÷'].includes(value)) {
                    chooseOperator(value);
                } else {
                    appendNumber(value);
                }
            }
        });
    });

    // Initialize display
    render(currentInput);
});
