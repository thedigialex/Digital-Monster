package thedigialex.digitalpet.ui

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.Button
import android.widget.EditText
import android.widget.ImageView
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
import thedigialex.digitalpet.util.SpriteManager
import thedigialex.digitalpet.util.TokenManager
import java.net.SocketTimeoutException

class LoginActivity : AppCompatActivity() {
    private var isLoginMode = true

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)



        val existingToken = TokenManager.getToken(applicationContext)
        if (existingToken != null) {
            validateTokenAndNavigate()
        }

        val actionButton = findViewById<Button>(R.id.actionButton)
        val switchButton = findViewById<Button>(R.id.switchButton)

        actionButton.setOnClickListener {
            if (isLoginMode) {
                val email = findViewById<EditText>(R.id.emailEditText).text.toString()
                val password = findViewById<EditText>(R.id.passwordEditText).text.toString()
                performLogin(email, password)
            } else {
                val name = findViewById<EditText>(R.id.nameEditText).text.toString()
                val email = findViewById<EditText>(R.id.emailEditText).text.toString()
                val password = findViewById<EditText>(R.id.passwordEditText).text.toString()
                performRegistration(name, email, password)
            }
        }

        switchButton.setOnClickListener {
            toggleMode()
        }
    }

    private fun toggleMode() {
        isLoginMode = !isLoginMode
        val nameEditText = findViewById<EditText>(R.id.nameEditText)
        val actionButton = findViewById<Button>(R.id.actionButton)
        val switchButton = findViewById<Button>(R.id.switchButton)

        if (isLoginMode) {
            nameEditText.visibility = View.GONE
            actionButton.text = "Login"
            switchButton.text = "Create Account"
        } else {
            nameEditText.visibility = View.VISIBLE
            actionButton.text = "Register"
            switchButton.text = "Login"
        }
    }

    private fun performLogin(email: String, password: String) {
        val progressBar = findViewById<ProgressBar>(R.id.progressBar)
        progressBar.visibility = View.VISIBLE

        CoroutineScope(Dispatchers.IO).launch {
            try {
                val api = RetrofitInstance.getApi(applicationContext)
                val response = api.loginUser(email, password)
                withContext(Dispatchers.Main) {
                    progressBar.visibility = View.GONE

                    val responseBody = response.body()
                    if (response.isSuccessful && responseBody?.status == true) {
                        TokenManager.saveToken(applicationContext, responseBody.token)
                        navigateToMainActivity(responseBody.user)
                    } else {
                        Toast.makeText(applicationContext, "Login failed: ${response.errorBody()?.string()}", Toast.LENGTH_LONG).show()
                    }
                }
            } catch (e: SocketTimeoutException) {
                withContext(Dispatchers.Main) {
                    progressBar.visibility = View.GONE
                    Toast.makeText(applicationContext, "Connection timed out. Please check your network connection and try again.", Toast.LENGTH_LONG).show()
                }
            }
        }
    }

    private fun performRegistration(name: String, email: String, password: String) {
        val progressBar = findViewById<ProgressBar>(R.id.progressBar)
        progressBar.visibility = View.VISIBLE

        CoroutineScope(Dispatchers.IO).launch {
            try {
                val api = RetrofitInstance.getApi(applicationContext)
                val response = api.registerUser(name, email, password)
                withContext(Dispatchers.Main) {
                    progressBar.visibility = View.GONE

                    val responseBody = response.body()
                    if (response.isSuccessful && responseBody?.status == true) {
                        Toast.makeText(applicationContext, "Registration successful! Please log in.", Toast.LENGTH_LONG).show()
                        toggleMode()
                    } else {
                        Toast.makeText(applicationContext, "Registration failed: ${response.errorBody()?.string()}", Toast.LENGTH_LONG).show()
                    }
                }
            } catch (e: SocketTimeoutException) {
                withContext(Dispatchers.Main) {
                    progressBar.visibility = View.GONE
                    Toast.makeText(applicationContext, "Connection timed out. Please check your network connection and try again.", Toast.LENGTH_LONG).show()
                }
            }
        }
    }

    private fun validateTokenAndNavigate() {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                val api = RetrofitInstance.getApi(applicationContext)
                val response = api.validateToken()
                withContext(Dispatchers.Main) {
                    val responseBody = response.body()
                    if (response.isSuccessful && responseBody?.status == true) {
                        navigateToMainActivity(responseBody.user)
                    } else {
                        Toast.makeText(applicationContext, "Login failed: ${response.errorBody()?.string()}", Toast.LENGTH_LONG).show()
                    }
                }
            } catch (e: SocketTimeoutException) {
                withContext(Dispatchers.Main) {
                    Toast.makeText(applicationContext, "Connection timed out. Please check your network connection and try again.", Toast.LENGTH_LONG).show()
                }
            }
        }
    }

    private fun saveUserData(user: User) {
        val sharedPreferences = getSharedPreferences("UserData", Context.MODE_PRIVATE)
        sharedPreferences.edit().apply {
            putLong("userId", user.id)
            putString("userName", user.name)
            putString("userEmail", user.email)
            putString("userNickname", user.nickname)
            apply()
        }
    }

    private fun navigateToMainActivity(user: User) {
        saveUserData(user)
        val intent = Intent(this, MainActivity::class.java)
        startActivity(intent)
        finish()
    }
}
