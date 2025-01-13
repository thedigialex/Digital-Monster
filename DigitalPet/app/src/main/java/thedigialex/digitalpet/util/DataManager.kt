package thedigialex.digitalpet.util

import android.content.Context
import android.content.SharedPreferences
import android.util.Log
import com.google.gson.Gson
import com.google.gson.reflect.TypeToken
import thedigialex.digitalpet.model.entities.DigitalMonster
import thedigialex.digitalpet.model.entities.InventoryItem
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.model.entities.UserDigitalMonster
import thedigialex.digitalpet.model.entities.UserTrainingEquipment

object DataManager {
    private const val PREFS_FILE_NAME = "AuthPreferences"
    private const val TOKEN_KEY = "AuthToken"
    private const val USER_KEY = "User"
    private const val EGG_KEY = "Eggs"
    private const val USER_DIGITAL_MONSTER_KEY = "UserDigitalMonsters"
    private const val USER_TRAINING_EQUIPMENT_KEY = "UserTrainingEquipment"
    private const val INVENTORY_ITEM_KEY = "InventoryItem"

    fun saveToken(context: Context, token: String) {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        sharedPreferences.edit().putString(TOKEN_KEY, token).apply()
    }
    fun getToken(context: Context): String? {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        return sharedPreferences.getString(TOKEN_KEY, null)
    }

    fun saveUser(context: Context, user: User) {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = Gson().toJson(user)
        Log.d("userJson user", userJson.toString())
        sharedPreferences.edit().putString(USER_KEY, userJson).apply()
    }
    fun getUser(context: Context): User? {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = sharedPreferences.getString(USER_KEY, null)
        return userJson?.let { Gson().fromJson(it, User::class.java) }
    }

    fun saveDigitalMonsterEggs(context: Context, eggs: List<DigitalMonster>) {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = Gson().toJson(eggs)
        Log.d("userJson eggs", userJson)
        sharedPreferences.edit().putString(EGG_KEY, userJson).apply()
    }
    fun getDigitalMonsterEggs(context: Context): List<DigitalMonster>? {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = sharedPreferences.getString(EGG_KEY, null)
        return if (userJson != null) {
            val type = object : TypeToken<List<DigitalMonster>>() {}.type
            Gson().fromJson<List<DigitalMonster>>(userJson, type)
        } else {
            null
        }
    }

    fun saveUserDigitalMonsters(context: Context, userDigitalMonsters: List<UserDigitalMonster>?) {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = Gson().toJson(userDigitalMonsters)
        Log.d("userJson userDigitalMonsters", userJson)
        sharedPreferences.edit().putString(USER_DIGITAL_MONSTER_KEY, userJson).apply()
    }
    fun getUserDigitalMonsters(context: Context): List<UserDigitalMonster>? {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = sharedPreferences.getString(USER_DIGITAL_MONSTER_KEY, null)
        return if (userJson != null) {
            val type = object : TypeToken<List<UserDigitalMonster>>() {}.type
            Gson().fromJson<List<UserDigitalMonster>>(userJson, type)
        } else {
            null
        }
    }

    fun saveUserTrainingEquipment(context: Context, userTrainingEquipmentList: List<UserTrainingEquipment>) {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = Gson().toJson(userTrainingEquipmentList)
        Log.d("userJson training", userJson)
        sharedPreferences.edit().putString(USER_TRAINING_EQUIPMENT_KEY, userJson).apply()
    }
    fun getUserTrainingEquipment(context: Context): List<UserTrainingEquipment>? {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = sharedPreferences.getString(USER_TRAINING_EQUIPMENT_KEY, null)
        return if (userJson != null) {
            val type = object : TypeToken<List<UserTrainingEquipment>>() {}.type
            Gson().fromJson<List<UserTrainingEquipment>>(userJson, type)
        } else {
            null
        }
    }

    fun saveInventoryItems(context: Context, inventoryItems: List<InventoryItem>) {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = Gson().toJson(inventoryItems)
        Log.d("userJson item", userJson)
        sharedPreferences.edit().putString(INVENTORY_ITEM_KEY, userJson).apply()
    }
    fun getInventoryItems(context: Context): List<InventoryItem>? {
        val sharedPreferences: SharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        val userJson = sharedPreferences.getString(INVENTORY_ITEM_KEY, null)
        return if (userJson != null) {
            val type = object : TypeToken<List<InventoryItem>>() {}.type
            Gson().fromJson<List<InventoryItem>>(userJson, type)
        } else {
            null
        }
    }



    fun clearData(context: Context) {
        val sharedPreferences = context.getSharedPreferences(PREFS_FILE_NAME, Context.MODE_PRIVATE)
        sharedPreferences.edit().clear().apply()
    }
}
