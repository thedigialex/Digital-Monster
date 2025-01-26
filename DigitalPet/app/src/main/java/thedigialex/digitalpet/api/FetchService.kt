package thedigialex.digitalpet.api

import android.content.Context
import android.util.Log
import kotlinx.coroutines.CoroutineScope
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.launch
import kotlinx.coroutines.withContext
import thedigialex.digitalpet.model.entities.DigitalMonster
import thedigialex.digitalpet.model.entities.InventoryItem
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.model.entities.UserDigitalMonster
import thedigialex.digitalpet.model.entities.UserTrainingEquipment
import thedigialex.digitalpet.util.DataManager

class FetchService(private val context: Context) {

    private fun performAuthAction(action: suspend () -> Unit) {
        CoroutineScope(Dispatchers.IO).launch {
            try {
                action()
            } catch (e: Exception) {
                Log.e("network error", "An error occurred: ${e.message}")
            }
        }
    }

    fun checkToken(): Boolean {
        val token = DataManager.getToken(context)
        return token != null
    }

    fun validateToken(onLoginSuccess: () -> Unit, onLoginFailure: (String) -> Unit) = performAuthAction {
        val response = ApiClient.getApi(context).validateToken()
        if (response.isSuccessful && response.body()?.status == true) {
            response.body()?.let { loginResponse ->
                DataManager.saveToken(context, loginResponse.token)
                DataManager.saveUser(context, loginResponse.user)
                withContext(Dispatchers.Main) {
                    onLoginSuccess()
                }
            }
        } else {
            withContext(Dispatchers.Main) {
                onLoginFailure("Failed: ${response.body()?.message}")
            }
        }
    }

    fun performLogin(email: String, password: String, onLoginSuccess: () -> Unit, onLoginFailure: (String) -> Unit) {
        performAuthAction {
            val response = ApiClient.getApi(context).login(email, password)
            if (response.isSuccessful && response.body()?.status == true) {
                response.body()?.let { loginResponse ->
                    DataManager.saveToken(context, loginResponse.token)
                    DataManager.saveUser(context, loginResponse.user)
                    withContext(Dispatchers.Main) {
                        onLoginSuccess()
                    }
                }
            } else {
                withContext(Dispatchers.Main) {
                    onLoginFailure("Failed: ${response.body()?.message}")
                }
            }
        }
    }

    fun performRegistration(name: String, email: String, password: String, onLoginSuccess: () -> Unit, onLoginFailure: (String) -> Unit)  = performAuthAction {
       val response = ApiClient.getApi(context).registerUser(name, email, password)
       if (response.isSuccessful && response.body()?.status == true) {
           response.body()?.let { loginResponse ->
               DataManager.saveToken(context, loginResponse.token)
               DataManager.saveUser(context, loginResponse.user)
               withContext(Dispatchers.Main) {
                   onLoginSuccess()
               }
           }
       } else {
           withContext(Dispatchers.Main) {
               onLoginFailure("Failed: ${response.body()?.message}")
           }
       }
   }

    fun getDigitalMonsterEggs(dataRetrievalSuccess: () -> Unit, dataRetrievalFailure: (String) -> Unit) = performAuthAction {
        val response = ApiClient.getApi(context).getDigitalMonsterEggs()
        if (response.isSuccessful && response.body()?.status == true) {
            response.body()?.let { digitalMonstersResponse ->
                DataManager.saveDigitalMonsterEggs(context, digitalMonstersResponse.digitalMonsters)
                withContext(Dispatchers.Main) {
                    dataRetrievalSuccess()
                }
            }
        } else {
            withContext(Dispatchers.Main) {
                dataRetrievalFailure("Failed: ${response.body()?.message}")
            }
        }
    }

    fun getUserDigitalMonsters(dataRetrievalSuccess: () -> Unit, dataRetrievalFailure: (String) -> Unit) = performAuthAction {
        val response = ApiClient.getApi(context).getUserDigitalMonsters()
        if (response.isSuccessful && response.body()?.status == true) {
            response.body()?.let { userDigitalMonstersResponse ->
                DataManager.saveUserDigitalMonsters(context, userDigitalMonstersResponse.userDigitalMonsters)
                withContext(Dispatchers.Main) {
                    dataRetrievalSuccess()
                }
            }
        } else {
            withContext(Dispatchers.Main) {
                dataRetrievalFailure("Failed: ${response.body()?.message}")
            }
        }
    }

    fun getUserTrainingEquipment(dataRetrievalSuccess: () -> Unit, dataRetrievalFailure: (String) -> Unit) = performAuthAction {
        val response = ApiClient.getApi(context).getTrainingEquipment()
        if (response.isSuccessful && response.body()?.status == true) {
            response.body()?.let { userTrainingEquipmentResponse ->
                DataManager.saveUserTrainingEquipment(context, userTrainingEquipmentResponse.userTrainingEquipment)
              withContext(Dispatchers.Main) {
                  dataRetrievalSuccess()
              }
            }
        } else {
            withContext(Dispatchers.Main) {
                dataRetrievalFailure("Failed: ${response.body()?.message}")
            }
        }
    }

    fun getItemsForSale(user: User, itemType: String, context: Context, onSuccess: () -> Unit) {
        performAuthAction {
            val response = ApiClient.getApi(context).getItems(itemType)
            if (response.isSuccessful) {
                response.body()?.let { itemResponse ->
                    val items = itemResponse.items
                    if (items.isEmpty()) {
                        user.itemsForSale = null
                        onSuccess()
                    }
                    else{
                        val itemCount = items.size
                        var readySpritesCount = 0
                        fun checkAllSpritesReady() {
                            if (readySpritesCount == itemCount) {
                                CoroutineScope(Dispatchers.Main).launch {
                                    user.itemsForSale = items
                                    onSuccess()
                                }
                            }
                        }
                        items.forEach { item ->
                            item.setupSprite(context) {
                                readySpritesCount++
                                checkAllSpritesReady()
                            }
                        }
                    }
                }
            }
        }
    }

    fun getInventoryItems(dataRetrievalSuccess: () -> Unit, dataRetrievalFailure: (String) -> Unit) = performAuthAction {
        val response = ApiClient.getApi(context).getInventoryItems()
        if (response.isSuccessful && response.body()?.status == true) {
            response.body()?.let { inventoryItemResponse ->
                DataManager.saveInventoryItems(context, inventoryItemResponse.inventoryItems)
                withContext(Dispatchers.Main) {
                    dataRetrievalSuccess()
                }
            }
        } else {
            withContext(Dispatchers.Main) {
                dataRetrievalFailure("Failed: ${response.body()?.message}")
            }
        }
    }

    fun buyItem(user: User, id: Int, context: Context, onSuccess: () -> Unit) {
        performAuthAction {
            val response = ApiClient.getApi(context).buyItem(id)
            if (response.isSuccessful) {
                response.body()?.let { inventoryItemResponse ->
                    val newItem = inventoryItemResponse.inventoryItems
                    val itemCount = newItem.size
                    var readySpritesCount = 0
                    fun checkAllSpritesReady() {
                        if (readySpritesCount == itemCount) {
                            CoroutineScope(Dispatchers.Main).launch {
                                val newInventoryItem = newItem[0]
                                val inventory = user.inventoryItems as MutableList<InventoryItem>
                                val existingItemIndex = inventory.indexOfFirst { it.id == newInventoryItem.id }
                                if (existingItemIndex != -1) {
                                    inventory[existingItemIndex] = newInventoryItem
                                } else {
                                    inventory.add(newInventoryItem)
                                }
                                user.inventoryItems = inventory
                                onSuccess()
                            }
                        }
                    }
                    newItem.forEach { item ->
                        item.item.setupSprite(context) {
                            readySpritesCount++
                            checkAllSpritesReady()
                        }
                    }
                }
            }
        }
    }

    fun setupSpriteAndReturnItem(inventoryItem: InventoryItem, onSuccess: (InventoryItem?) -> Unit) {
        performAuthAction{
            inventoryItem.item.setupSprite(context) {
                onSuccess(inventoryItem)
            }
        }
    }

    fun setupSpriteAndReturnTrainingEquipment(userTrainingEquipment: UserTrainingEquipment, onSuccess: (UserTrainingEquipment?) -> Unit) {
        performAuthAction {
            userTrainingEquipment.trainingEquipment.setupSprite(context) {
                onSuccess(userTrainingEquipment)
            }
        }
    }

    fun createNewUserDigitalMonster(digitalMonsterId: Int, name: String, onSuccess: (UserDigitalMonster?) -> Unit) {
        performAuthAction {
            val response = ApiClient.getApi(context).createUserDigitalMonster(digitalMonsterId, name)
            if (response.isSuccessful && response.body()?.status == true) {
                response.body()?.userDigitalMonsters?.let { userDigitalMonsters ->
                    for (i in userDigitalMonsters.indices) {
                        setUpSpriteImages(userDigitalMonsters[i]) { updatedMonster ->
                            updatedMonster?.let {
                                CoroutineScope(Dispatchers.Main).launch {
                                    onSuccess(updatedMonster)
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    fun setUpSpriteImages(userDigitalMonster: UserDigitalMonster, onSuccess: (UserDigitalMonster?) -> Unit) {
        performAuthAction {
            val spriteType = when (userDigitalMonster.digital_monster.stage) {
                "Egg", "Fresh", "Child" -> "Data"
                else -> userDigitalMonster.type
            }
            setupSpriteAndReturn(userDigitalMonster, spriteType, onSuccess)
        }
    }

    private fun setupSpriteAndReturn(userDigitalMonster: UserDigitalMonster, type: String, onSuccess: (UserDigitalMonster?) -> Unit) {
        performAuthAction {
            userDigitalMonster.digital_monster.setupSprite(context, type) {
                onSuccess(userDigitalMonster)
            }
        }
    }

    fun setUpDigitalMonsterSpriteImages(digitalMonster: DigitalMonster, type: String, onSuccess: (DigitalMonster?) -> Unit) {
        performAuthAction {
            val spriteType = when (digitalMonster.stage) {
                "Egg", "Fresh", "Child" -> "Data"
                else -> type
            }
            setupDigitalMonsterSpriteAndReturn(digitalMonster, spriteType, onSuccess)
        }
    }

    private fun setupDigitalMonsterSpriteAndReturn(digitalMonster: DigitalMonster, type: String, onSuccess: (DigitalMonster?) -> Unit) {
        performAuthAction {
            digitalMonster.setupSprite(context, type) {
                onSuccess(digitalMonster)
            }
        }
    }

    fun saveUserDigitalMonster(userDigitalMonster: UserDigitalMonster, onSaveComplete: ((Boolean) -> Unit)? = null) {
        performAuthAction {
            val response = ApiClient.getApi(context).userDigitalMonsterUpdate(
                id = userDigitalMonster.id,
                name = userDigitalMonster.name,
                level = userDigitalMonster.level,
                exp = userDigitalMonster.exp,
                strength = userDigitalMonster.strength,
                agility = userDigitalMonster.agility,
                defense = userDigitalMonster.defense,
                mind = userDigitalMonster.mind,
                hunger = userDigitalMonster.hunger,
                exercise = userDigitalMonster.exercise,
                clean = userDigitalMonster.clean,
                energy = userDigitalMonster.energy,
                maxEnergy = userDigitalMonster.maxEnergy,
                wins = userDigitalMonster.wins,
                losses = userDigitalMonster.losses,
                trainings = userDigitalMonster.trainings,
                maxTrainings = userDigitalMonster.maxTrainings,
                currentEvoPoints = userDigitalMonster.currentEvoPoints,
                sleepStartedAt = userDigitalMonster.sleepStartedAt
            )
            withContext(Dispatchers.Main) {
                onSaveComplete?.invoke(response.isSuccessful)
            }
        }
    }

    fun evolveUserDigitalMonster(dataRetrievalSuccess: (UserDigitalMonster?) -> Unit) {
        performAuthAction {
            val response = ApiClient.getApi(context).evolve()
            if (response.isSuccessful && response.body()?.status == true) {
                response.body()?.userDigitalMonsters?.let { userDigitalMonsters ->
                    for (i in userDigitalMonsters.indices) {
                        setUpSpriteImages(userDigitalMonsters[i]) { updatedMonster ->
                            updatedMonster?.let {
                                CoroutineScope(Dispatchers.Main).launch {
                                    dataRetrievalSuccess(updatedMonster)
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    fun updateInventoryItem(inventoryItem: InventoryItem) {
        performAuthAction{
            ApiClient.getApi(context).updateInventoryItem(inventoryItem.id)
        }
    }
}