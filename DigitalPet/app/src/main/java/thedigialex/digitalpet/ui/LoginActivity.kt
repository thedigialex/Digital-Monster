package thedigialex.digitalpet.ui

import android.content.Intent
import android.os.Bundle
import android.view.View
import android.widget.Button
import android.widget.EditText
import android.widget.ProgressBar
import android.widget.TextView
import androidx.appcompat.app.AppCompatActivity
import thedigialex.digitalpet.R
import thedigialex.digitalpet.api.FetchService

class LoginActivity : AppCompatActivity() {
    private var isLoginMode = true
    private var currentProgress: Int = 0
    private lateinit var errorText: TextView
    private lateinit var progressBar: ProgressBar
    private lateinit var actionButton: Button
    private lateinit var fetchService: FetchService

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)
        progressBar = findViewById(R.id.progressBar)
        actionButton = findViewById(R.id.actionButton)
        errorText = findViewById(R.id.errorText)
        findViewById<Button>(R.id.switchButton).setOnClickListener { toggleMode() }

        fetchService = FetchService(this)
        if(fetchService.checkToken()){ validateToken() }

        findViewById<Button>(R.id.actionButton).setOnClickListener { handleAction() }
    }

    private fun handleAction() {
        val name = findViewById<EditText>(R.id.nameEditText).text.toString()
        val email = findViewById<EditText>(R.id.emailEditText).text.toString()
        val password = findViewById<EditText>(R.id.passwordEditText).text.toString()
        val confirmPassword = findViewById<EditText>(R.id.confirmPasswordEditText).text.toString()

        var issue = false
        errorText.visibility = View.GONE
        actionButton.isClickable = false
        if(password.isEmpty()) {
            showIssue("Enter Password")
            issue = true
        }
        if(email.isEmpty()) {
            showIssue("Enter Email Address")
            issue = true
        }

        if(isLoginMode && !issue) {
            performLogin(email, password)
        }

        if(!isLoginMode) {
            if(password != confirmPassword) {
                showIssue("Passwords do not match")
                issue = true
            }
            if(name.isEmpty()) {
                showIssue("Enter Name")
                issue = true
            }
            if(!issue) {
                performRegistration(name, email, password)
            }
        }
    }

    private fun toggleMode() {
        errorText.visibility = View.GONE
        isLoginMode = !isLoginMode
        findViewById<EditText>(R.id.nameEditText).visibility = if (isLoginMode) View.GONE else View.VISIBLE
        findViewById<EditText>(R.id.confirmPasswordEditText).visibility = if (isLoginMode) View.GONE else View.VISIBLE
        findViewById<Button>(R.id.actionButton).text = getString(if (isLoginMode) R.string.login else R.string.register)
        findViewById<Button>(R.id.switchButton).text = getString(if (isLoginMode) R.string.register else R.string.login)
        findViewById<TextView>(R.id.formTitle).text = getString(if (isLoginMode) R.string.login else R.string.register)
    }

    private fun showIssue(errorMessage: String){
        errorText.text = errorMessage
        errorText.visibility = View.VISIBLE
        actionButton.isClickable = true
    }

    private fun validateToken() {
        actionButton.isClickable = false
        fetchService.validateToken(
            onLoginSuccess = {
                fetchAndStoreData()
            },
            onLoginFailure = { errorMessage ->
                showIssue(errorMessage)
            }
        )
    }

    private fun performLogin(email: String, password: String) {
        fetchService.performLogin(
            email = email,
            password = password,
            onLoginSuccess = {
                fetchAndStoreData()
            },
            onLoginFailure = { errorMessage ->
                showIssue(errorMessage)
            }
        )
    }

    private fun performRegistration(name: String, email: String, password: String) {
        fetchService.performRegistration(
            name = name,
            email = email,
            password = password,
            onLoginSuccess = {
                fetchAndStoreData()
            },
            onLoginFailure = { errorMessage ->
                showIssue(errorMessage)
            }
        )
    }

    private fun updateLoading() {
        currentProgress += 20
        progressBar.progress = currentProgress
    }

    private fun fetchAndStoreData() {
        updateLoading()
        fetchService.getDigitalMonsterEggs(
            dataRetrievalSuccess = {
                updateLoading()
                fetchService.getUserDigitalMonsters(
                    dataRetrievalSuccess = {
                        updateLoading()
                        fetchService.getInventoryItems(
                            dataRetrievalSuccess = {
                                updateLoading()
                                fetchService.getUserTrainingEquipment(
                                    dataRetrievalSuccess = {
                                        updateLoading()
                                        //navigateToMainActivity()
                                    },
                                    dataRetrievalFailure = { errorMessage ->
                                        showIssue(errorMessage)
                                    }
                                )
                            },
                            dataRetrievalFailure = { errorMessage ->
                                showIssue(errorMessage)
                            }
                        )
                    },
                    dataRetrievalFailure = { errorMessage ->
                        showIssue(errorMessage)
                    }
                )
            },
            dataRetrievalFailure = { errorMessage ->
                showIssue(errorMessage)
            }
        )

    }

    private fun navigateToMainActivity() {
        startActivity(Intent(this, DashboardActivity::class.java))
        finish()
    }
}
