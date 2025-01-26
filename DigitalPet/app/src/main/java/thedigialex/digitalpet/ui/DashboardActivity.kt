package thedigialex.digitalpet.ui

import android.os.Bundle
import android.util.Log
import android.widget.ProgressBar
import androidx.appcompat.app.AppCompatActivity
import thedigialex.digitalpet.R
import thedigialex.digitalpet.api.FetchService
import thedigialex.digitalpet.controller.CaseController
import thedigialex.digitalpet.model.entities.User
import thedigialex.digitalpet.util.DataManager

class DashboardActivity : AppCompatActivity() {
    private lateinit var user: User
    private lateinit var fetchService: FetchService
    private lateinit var caseController: CaseController
    private var currentProgress: Int = 0
    private lateinit var progressBar: ProgressBar

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_dashboard)
        progressBar = findViewById(R.id.progressBar)
        fetchService = FetchService(this)
        setUpData()
        Log.d("User Data", user.toString())
    }

    override fun onPause() {
        super.onPause()
        if(user.mainDigitalMonster != null) {
            fetchService.saveUserDigitalMonster(user.mainDigitalMonster!!)
        }
    }

    private fun setUpData() {
        user = DataManager.getUser(applicationContext)!!
        user.eggs = DataManager.getDigitalMonsterEggs(applicationContext)
        user.userDigitalMonster = DataManager.getUserDigitalMonsters(applicationContext)
        user.inventoryItems = DataManager.getInventoryItems(applicationContext)
        user.trainingEquipments = DataManager.getUserTrainingEquipment(applicationContext)
        val totalLength = user.eggs?.size!! +
                user.userDigitalMonster?.size!! +
                user.inventoryItems?.size!! +
                user.trainingEquipments?.size!!
        progressBar.max = totalLength
        setUpImages()
    }

    private fun setUpImages() {
        user.eggs?.let{ eggs ->
            for (i in eggs.indices) {
                fetchService.setUpDigitalMonsterSpriteImages(eggs[i], "Data") { updatedMonster ->
                    updatedMonster?.let {
                        user.eggs = user.eggs!!.toMutableList().apply {
                            this[i] = it
                        }
                        updateLoading()
                    }
                }
            }
        }
        user.userDigitalMonster?.let{ userDigitalMonsters ->
            for (i in userDigitalMonsters.indices) {
                fetchService.setUpSpriteImages(userDigitalMonsters[i]) { updatedMonster ->
                    updatedMonster?.let {
                        user.userDigitalMonster = user.userDigitalMonster!!.toMutableList().apply {
                            this[i] = it
                        }
                        updateLoading()
                    }
                }
            }
        }
        user.inventoryItems?.let { items ->
            for (i in items.indices) {
                fetchService.setupSpriteAndReturnItem(items[i]) { updatedItem ->
                    updatedItem?.let {
                        user.inventoryItems = user.inventoryItems!!.toMutableList().apply {
                            this[i] = it
                        }
                    }
                    updateLoading()
                }
            }
        }
        user.trainingEquipments?.let { trainingEquipments ->
            for (i in trainingEquipments.indices) {
                fetchService.setupSpriteAndReturnTrainingEquipment(trainingEquipments[i]) { updatedItem ->
                    updatedItem?.let {
                        user.trainingEquipments = user.trainingEquipments!!.toMutableList().apply {
                            this[i] = it
                        }
                    }
                    updateLoading()
                }
            }
        }

    }

    private fun updateLoading() {
        currentProgress += 1
        if(currentProgress >= progressBar.max) {
            currentProgress = 0
            caseController = CaseController(findViewById(R.id.caseBackground),this, fetchService, user)
        }
        progressBar.progress = currentProgress
    }
}