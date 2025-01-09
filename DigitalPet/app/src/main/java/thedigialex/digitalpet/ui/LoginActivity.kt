package thedigialex.digitalpet.ui

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.Button
import android.widget.EditText
import android.widget.ProgressBar
import android.widget.TextView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import thedigialex.digitalpet.R
import thedigialex.digitalpet.api.FetchService

class LoginActivity : AppCompatActivity() {
    private var isLoginMode = true
    private lateinit var fetchService: FetchService

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)
        fetchService = FetchService(this) { isLoading -> showLoading(isLoading) }
        if(fetchService.checkToken()){ validateToken() }
        findViewById<Button>(R.id.actionButton).setOnClickListener { handleAction() }
        findViewById<Button>(R.id.switchButton).setOnClickListener { toggleMode() }
    }

    private fun handleAction() {
        val name = findViewById<EditText>(R.id.nameEditText).text.toString()
        val email = findViewById<EditText>(R.id.emailEditText).text.toString()
        val password = findViewById<EditText>(R.id.passwordEditText).text.toString()
        val confirmPassword = findViewById<EditText>(R.id.confirmPasswordEditText).text.toString()

        when {
            email.isEmpty() -> showToast("Enter a valid email address")
            password.isEmpty() -> showToast("Password cannot be empty")
            isLoginMode -> performLogin(email, password)
            name.isEmpty() -> showToast("Name cannot be empty")
            password != confirmPassword -> showToast("Passwords do not match")
            else -> performRegistration(name, email, password)
        }
    }

    private fun toggleMode() {
        isLoginMode = !isLoginMode
        findViewById<EditText>(R.id.nameEditText).visibility = if (isLoginMode) View.GONE else View.VISIBLE
        findViewById<EditText>(R.id.confirmPasswordEditText).visibility = if (isLoginMode) View.GONE else View.VISIBLE
        findViewById<Button>(R.id.actionButton).text = getString(if (isLoginMode) R.string.login else R.string.register)
        findViewById<Button>(R.id.switchButton).text = getString(if (isLoginMode) R.string.register else R.string.login)
        findViewById<TextView>(R.id.formTitle).text = getString(if (isLoginMode) R.string.login else R.string.register)
    }

    private fun performLogin(email: String, password: String) {
        fetchService.performLogin(
            email = email,
            password = password,
            onLoginSuccess = {
                navigateToMainActivity()
            },
            onLoginFailure = { errorMessage ->
                showToast(errorMessage)
            }
        )
    }

    private fun performRegistration(name: String, email: String, password: String) {
        fetchService.performRegistration(
            name = name,
            email = email,
            password = password,
            onLoginSuccess = {
                navigateToMainActivity()
            },
            onLoginFailure = { errorMessage ->
                showToast(errorMessage)
            }
        )
    }

    private fun validateToken() {
        fetchService.validateToken(
            onLoginSuccess = {
                navigateToMainActivity()
            },
            onLoginFailure = { errorMessage ->
                showToast(errorMessage)
            }
        )
    }

    private fun showLoading(isLoading: Boolean) {
        val progressBar = findViewById<ProgressBar>(R.id.progressBar)
        val actionButton = findViewById<Button>(R.id.actionButton)
        progressBar.visibility = if (isLoading) View.VISIBLE else View.GONE
        actionButton.isClickable = !isLoading
    }

    private fun showToast(message: String) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show()
    }

    private fun navigateToMainActivity() {
        startActivity(Intent(this, DashboardActivity::class.java))
        finish()
    }
}
