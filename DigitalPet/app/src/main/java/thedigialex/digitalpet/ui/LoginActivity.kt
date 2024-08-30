package thedigialex.digitalpet.ui

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.Button
import android.widget.EditText
import android.widget.ProgressBar
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import thedigialex.digitalpet.R
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.network.RetrofitInstance
import thedigialex.digitalpet.util.TokenManager
import java.net.SocketTimeoutException

class LoginActivity : AppCompatActivity() {
    private var isLoginMode = true

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        TokenManager.getToken(applicationContext)?.let { validateTokenAndNavigate() }

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
    }

    private fun performLogin(email: String, password: String) = performAuthAction {
        val response = RetrofitInstance.getApi(applicationContext).loginUser(email, password)
        if (response.isSuccessful && response.body()?.status == true) {
            response.body()?.let {
                TokenManager.saveToken(applicationContext, it.token)
                navigateToMainActivity(it.user)
            }
        } else showToast("Login failed: ${response.errorBody()?.string()}")
    }

    private fun performRegistration(name: String, email: String, password: String) = performAuthAction {
        val response = RetrofitInstance.getApi(applicationContext).registerUser(name, email, password)
        if (response.isSuccessful && response.body()?.status == true) {
            showToast("Registration successful! Please log in.")
            toggleMode()
        } else showToast("Registration failed: ${response.errorBody()?.string()}")
    }

    private fun validateTokenAndNavigate() = performAuthAction {
        val response = RetrofitInstance.getApi(applicationContext).validateToken()
        if (response.isSuccessful && response.body()?.status == true) {
            navigateToMainActivity(response.body()?.user!!)
        } else showToast("Login failed: ${response.errorBody()?.string()}")
    }

    private fun performAuthAction(action: suspend () -> Unit) {
        val progressBar = findViewById<ProgressBar>(R.id.progressBar)
        val actionButton = findViewById<Button>(R.id.actionButton)

        showLoading(progressBar, actionButton, true)
        CoroutineScope(Dispatchers.IO).launch {
            try {
                action()
            } catch (e: SocketTimeoutException) {
                withContext(Dispatchers.Main) {
                    showToast("Connection timed out. Please check your network connection and try again.")
                }
            } finally {
                withContext(Dispatchers.Main) {
                    showLoading(progressBar, actionButton, false)
                }
            }
        }
    }

    private fun showLoading(progressBar: ProgressBar, button: Button, isLoading: Boolean) {
        progressBar.visibility = if (isLoading) View.VISIBLE else View.GONE
        button.isClickable = !isLoading
    }

    private fun showToast(message: String) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show()
    }

    private fun saveUserData(user: User) {
        getSharedPreferences("UserData", Context.MODE_PRIVATE).edit().apply {
            putLong("userId", user.id)
            putString("userName", user.name)
            putString("userEmail", user.email)
            putInt("userBits", user.bits)
            apply()
        }
    }

    private fun navigateToMainActivity(user: User) {
        saveUserData(user)
        startActivity(Intent(this, MainActivity::class.java))
        finish()
    }
}
