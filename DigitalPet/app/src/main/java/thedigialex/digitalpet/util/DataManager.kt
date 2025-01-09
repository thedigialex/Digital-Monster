package thedigialex.digitalpet.util

import android.content.Context
import android.content.SharedPreferences
import com.google.gson.Gson
import thedigialex.digitalpet.model.entities.User

object DataManager {
    private const val PREFS_FILE_NAME = "AuthPreferences"
    private const val TOKEN_KEY = "AuthToken"
    private const val USER_KEY = "User"

    fun saveToken(context: Context, token: String) {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        sharedPreferences.edit().putString(TOKEN_KEY, token).apply()
    }

    fun saveUser(context: Context, user: User) {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = Gson().toJson(user)
        sharedPreferences.edit().putString(USER_KEY, userJson).apply()
    }

    fun getUser(context: Context): User? {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = sharedPreferences.getString(USER_KEY, null)
        return userJson?.let { Gson().fromJson(it, User::class.java) }
    }

    fun getToken(context: Context): String? {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        return sharedPreferences.getString(TOKEN_KEY, null)
    }

    fun clearData(context: Context) {
        val sharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        sharedPreferences.edit().clear().apply()
    }
}
