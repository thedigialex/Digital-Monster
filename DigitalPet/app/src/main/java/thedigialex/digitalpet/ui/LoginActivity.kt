package thedigialex.digitalpet.ui

import android.content.Context
import android.content.Intent
import android.os.Bundle
import android.widget.Button
import android.widget.EditText
import android.widget.ImageView
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import thedigialex.digitalpet.R
import thedigialex.digitalpet.model.User
import thedigialex.digitalpet.network.RetrofitInstance
import thedigialex.digitalpet.util.SpriteManager
import thedigialex.digitalpet.util.TokenManager

class LoginActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        val imageView = findViewById<ImageView>(R.id.imageView)
        val resourceId = R.drawable.eggs
        val tilesPerRow = 8
        val sprites = SpriteManager.splitSpriteSheet(resources, resourceId, tilesPerRow)
        if (sprites.isNotEmpty()) {
            imageView.setImageBitmap(sprites[0])
            var currentIndex = 0
            imageView.setOnClickListener {
                currentIndex = (currentIndex + 1) % sprites.size
                imageView.setImageBitmap(sprites[currentIndex])
            }
        }

        val existingToken = TokenManager.getToken(applicationContext)
        if (existingToken != null) {
            validateTokenAndNavigate()
        }
        findViewById<Button>(R.id.loginButton).setOnClickListener {
            val email = findViewById<EditText>(R.id.emailEditText).text.toString()
            val password = findViewById<EditText>(R.id.passwordEditText).text.toString()
            performLogin(email, password)
        }
    }

    private fun performLogin(email: String, password: String) {
        CoroutineScope(Dispatchers.IO).launch {
            val api = RetrofitInstance.getApi(applicationContext)
            val response = api.loginUser(email, password)
            runOnUiThread {
                val responseBody = response.body()
                if (response.isSuccessful && responseBody?.status == true) {
                    TokenManager.saveToken(applicationContext, responseBody.token)
                    navigateToMainActivity(responseBody.user)
                } else {
                    Toast.makeText(applicationContext, "Login failed: ${response.errorBody()?.string()}", Toast.LENGTH_LONG).show()
                }
            }

        }
    }
    private fun validateTokenAndNavigate() {
        CoroutineScope(Dispatchers.IO).launch {
            val api = RetrofitInstance.getApi(applicationContext)
            val response = api.validateToken()
            runOnUiThread {
                val responseBody = response.body()
                if (response.isSuccessful && responseBody?.status == true) {
                    navigateToMainActivity(responseBody.user)
                } else {
                    Toast.makeText(applicationContext, "Login failed: ${response.errorBody()?.string()}", Toast.LENGTH_LONG).show()
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
